<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Stock;
use App\Models\Unit;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SaleLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private User $cashier;
    private Warehouse $warehouse;
    private Product $product;
    private Stock $stock;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $cashierRole = Role::create(['name' => 'cashier']);
        Role::create(['name' => 'admin']);

        // Create cashier user
        $this->cashier = User::factory()->create();
        $this->cashier->assignRole($cashierRole);

        // Create warehouse
        $this->warehouse = Warehouse::create([
            'name' => 'Test Warehouse',
            'code' => 'WH-TEST',
            'is_active' => true,
        ]);

        // Create unit and category
        $unit = Unit::create([
            'name' => 'Piece',
            'abbreviation' => 'pcs',
            'is_active' => true,
        ]);

        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'is_active' => true,
        ]);

        // Create product
        $this->product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'sku' => 'TEST-001',
            'barcode' => '1234567890',
            'cost_price' => 5000,
            'selling_price' => 10000,
            'stock_alert_level' => 5,
            'track_stock' => true,
            'is_active' => true,
            'category_id' => $category->id,
            'unit_id' => $unit->id,
        ]);

        // Create stock
        $this->stock = Stock::create([
            'product_id' => $this->product->id,
            'warehouse_id' => $this->warehouse->id,
            'quantity' => 100,
        ]);
    }

    // ==========================================
    // CREATE SALE TESTS
    // ==========================================

    public function test_cashier_can_create_sale(): void
    {
        $response = $this->actingAs($this->cashier)->post(route('cashier.pos.store'), [
            'warehouse_id' => $this->warehouse->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 2],
            ],
            'discount_amount' => 0,
            'tax_amount' => 0,
            'payment_method' => 'cash',
            'paid_amount' => 20000,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify sale was created
        $this->assertDatabaseCount('sales', 1);
        $sale = Sale::first();
        $this->assertEquals('completed', $sale->status);
        $this->assertEquals(20000, (float) $sale->total_amount);
        $this->assertEquals(20000, (float) $sale->subtotal);
    }

    public function test_sale_creates_correct_subtotals(): void
    {
        $this->actingAs($this->cashier)->post(route('cashier.pos.store'), [
            'warehouse_id' => $this->warehouse->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 3],
            ],
            'discount_amount' => 5000,
            'tax_amount' => 1000,
            'payment_method' => 'cash',
            'paid_amount' => 26000,
        ]);

        $sale = Sale::first();
        $this->assertEquals(30000, (float) $sale->subtotal); // 3 × 10000
        $this->assertEquals(5000, (float) $sale->discount_amount);
        $this->assertEquals(1000, (float) $sale->tax_amount);
        $this->assertEquals(26000, (float) $sale->total_amount); // 30000 - 5000 + 1000
    }

    public function test_sale_deducts_stock(): void
    {
        $this->actingAs($this->cashier)->post(route('cashier.pos.store'), [
            'warehouse_id' => $this->warehouse->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 10],
            ],
            'payment_method' => 'cash',
            'paid_amount' => 100000,
        ]);

        $this->stock->refresh();
        $this->assertEquals(90, (float) $this->stock->quantity);
    }

    public function test_sale_creates_payment_record(): void
    {
        $this->actingAs($this->cashier)->post(route('cashier.pos.store'), [
            'warehouse_id' => $this->warehouse->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 1],
            ],
            'payment_method' => 'transfer',
            'paid_amount' => 10000,
            'payment_reference' => 'TRF-123',
        ]);

        $this->assertDatabaseHas('sale_payments', [
            'method' => 'transfer',
            'amount' => 10000,
            'reference_number' => 'TRF-123',
        ]);
    }

    public function test_sale_fails_with_insufficient_stock(): void
    {
        $response = $this->actingAs($this->cashier)->post(route('cashier.pos.store'), [
            'warehouse_id' => $this->warehouse->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 999],
            ],
            'payment_method' => 'cash',
            'paid_amount' => 9990000,
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('sales', 0);
    }

    public function test_sale_fails_with_insufficient_payment(): void
    {
        $response = $this->actingAs($this->cashier)->post(route('cashier.pos.store'), [
            'warehouse_id' => $this->warehouse->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 1],
            ],
            'payment_method' => 'cash',
            'paid_amount' => 1000, // Less than 10000
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('sales', 0);
    }

    public function test_sale_validation_requires_items(): void
    {
        $response = $this->actingAs($this->cashier)->post(route('cashier.pos.store'), [
            'warehouse_id' => $this->warehouse->id,
            'items' => [],
            'payment_method' => 'cash',
            'paid_amount' => 10000,
        ]);

        $response->assertSessionHasErrors('items');
    }

    // ==========================================
    // VOID SALE TESTS
    // ==========================================

    public function test_cashier_can_void_sale(): void
    {
        // Create a sale first
        $this->actingAs($this->cashier)->post(route('cashier.pos.store'), [
            'warehouse_id' => $this->warehouse->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 5],
            ],
            'payment_method' => 'cash',
            'paid_amount' => 50000,
        ]);

        $sale = Sale::first();
        $this->stock->refresh();
        $this->assertEquals(95, (float) $this->stock->quantity);

        // Void the sale
        $response = $this->actingAs($this->cashier)->patch(
            route('cashier.sales.void', $sale),
            ['reason' => 'Customer changed mind']
        );

        $response->assertRedirect();

        $sale->refresh();
        $this->assertEquals('voided', $sale->status);

        // Stock should be restored
        $this->stock->refresh();
        $this->assertEquals(100, (float) $this->stock->quantity);
    }

    public function test_voided_sale_cannot_be_voided_again(): void
    {
        $this->actingAs($this->cashier)->post(route('cashier.pos.store'), [
            'warehouse_id' => $this->warehouse->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 1],
            ],
            'payment_method' => 'cash',
            'paid_amount' => 10000,
        ]);

        $sale = Sale::first();

        // Void once
        $this->actingAs($this->cashier)->patch(route('cashier.sales.void', $sale));

        // Try void again — should fail
        $response = $this->actingAs($this->cashier)->patch(route('cashier.sales.void', $sale));
        $response->assertSessionHasErrors();
    }

    // ==========================================
    // REFUND SALE TESTS
    // ==========================================

    public function test_cashier_can_refund_sale(): void
    {
        // Create a sale
        $this->actingAs($this->cashier)->post(route('cashier.pos.store'), [
            'warehouse_id' => $this->warehouse->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 5],
            ],
            'payment_method' => 'cash',
            'paid_amount' => 50000,
        ]);

        $sale = Sale::first();
        $saleItem = $sale->items()->first();

        // Refund 2 items
        $response = $this->actingAs($this->cashier)->post(
            route('cashier.sales.refunds.store', $sale),
            [
                'method' => 'cash',
                'reason' => 'Defective product',
                'items' => [
                    ['sale_item_id' => $saleItem->id, 'quantity' => 2],
                ],
            ]
        );

        $response->assertRedirect();

        // Verify refund was created
        $this->assertDatabaseCount('sale_refunds', 1);
        $this->assertDatabaseHas('sale_refunds', [
            'total_amount' => 20000, // 2 × 10000
            'status' => 'completed',
        ]);

        // Stock should be restored for refunded items
        $this->stock->refresh();
        $this->assertEquals(97, (float) $this->stock->quantity); // 100 - 5 + 2
    }

    public function test_partial_refund_updates_sale_status(): void
    {
        $this->actingAs($this->cashier)->post(route('cashier.pos.store'), [
            'warehouse_id' => $this->warehouse->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 5],
            ],
            'payment_method' => 'cash',
            'paid_amount' => 50000,
        ]);

        $sale = Sale::first();
        $saleItem = $sale->items()->first();

        // Partial refund (2 of 5)
        $this->actingAs($this->cashier)->post(
            route('cashier.sales.refunds.store', $sale),
            [
                'method' => 'cash',
                'items' => [
                    ['sale_item_id' => $saleItem->id, 'quantity' => 2],
                ],
            ]
        );

        $sale->refresh();
        $this->assertEquals('partially_refunded', $sale->status);
    }

    public function test_full_refund_updates_sale_status(): void
    {
        $this->actingAs($this->cashier)->post(route('cashier.pos.store'), [
            'warehouse_id' => $this->warehouse->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 3],
            ],
            'payment_method' => 'cash',
            'paid_amount' => 30000,
        ]);

        $sale = Sale::first();
        $saleItem = $sale->items()->first();

        // Full refund (3 of 3)
        $this->actingAs($this->cashier)->post(
            route('cashier.sales.refunds.store', $sale),
            [
                'method' => 'cash',
                'items' => [
                    ['sale_item_id' => $saleItem->id, 'quantity' => 3],
                ],
            ]
        );

        $sale->refresh();
        $this->assertEquals('refunded', $sale->status);

        // Stock should be fully restored
        $this->stock->refresh();
        $this->assertEquals(100, (float) $this->stock->quantity);
    }

    public function test_refund_cannot_exceed_purchased_quantity(): void
    {
        $this->actingAs($this->cashier)->post(route('cashier.pos.store'), [
            'warehouse_id' => $this->warehouse->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 2],
            ],
            'payment_method' => 'cash',
            'paid_amount' => 20000,
        ]);

        $sale = Sale::first();
        $saleItem = $sale->items()->first();

        // Try to refund more than purchased
        $response = $this->actingAs($this->cashier)->post(
            route('cashier.sales.refunds.store', $sale),
            [
                'method' => 'cash',
                'items' => [
                    ['sale_item_id' => $saleItem->id, 'quantity' => 99],
                ],
            ]
        );

        $response->assertSessionHasErrors();
    }

    // ==========================================
    // CHANGE CALCULATION TESTS
    // ==========================================

    public function test_change_amount_is_correctly_calculated(): void
    {
        $this->actingAs($this->cashier)->post(route('cashier.pos.store'), [
            'warehouse_id' => $this->warehouse->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 1],
            ],
            'payment_method' => 'cash',
            'paid_amount' => 15000,
        ]);

        $sale = Sale::first();
        $this->assertEquals(10000, (float) $sale->total_amount);
        $this->assertEquals(15000, (float) $sale->paid_amount);
        $this->assertEquals(5000, (float) $sale->change_amount);
    }

    // ==========================================
    // STOCK MOVEMENT AUDIT TESTS
    // ==========================================

    public function test_sale_creates_stock_movement_record(): void
    {
        $this->actingAs($this->cashier)->post(route('cashier.pos.store'), [
            'warehouse_id' => $this->warehouse->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 3],
            ],
            'payment_method' => 'cash',
            'paid_amount' => 30000,
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'warehouse_id' => $this->warehouse->id,
            'type' => 'sale',
            'quantity_before' => 100,
            'quantity_change' => -3,
            'quantity_after' => 97,
        ]);
    }

    public function test_void_creates_stock_movement_record(): void
    {
        $this->actingAs($this->cashier)->post(route('cashier.pos.store'), [
            'warehouse_id' => $this->warehouse->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 3],
            ],
            'payment_method' => 'cash',
            'paid_amount' => 30000,
        ]);

        $sale = Sale::first();
        $this->actingAs($this->cashier)->patch(route('cashier.sales.void', $sale));

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'type' => 'sale_void',
            'quantity_change' => 3,
        ]);
    }
}
