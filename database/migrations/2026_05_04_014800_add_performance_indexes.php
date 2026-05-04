<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add composite indexes for hot query paths used in dashboards and reports.
     *
     * These indexes target the most frequent WHERE + ORDER BY combinations
     * across Owner Reports, Admin Dashboard, and POS queries.
     */
    public function up(): void
    {
        // sales: composite (status, sold_at) — used in every report query
        Schema::table('sales', function (Blueprint $table) {
            $table->index(['status', 'sold_at'], 'sales_status_sold_at_index');
        });

        // sale_refunds: composite (status, refunded_at) — used in net sales & profit
        Schema::table('sale_refunds', function (Blueprint $table) {
            $table->index(['status', 'refunded_at'], 'sale_refunds_status_refunded_at_index');
        });

        // purchases: composite (status, purchased_at) — used in purchase reports
        Schema::table('purchases', function (Blueprint $table) {
            $table->index(['status', 'purchased_at'], 'purchases_status_purchased_at_index');
        });

        // products: composite (is_active, track_stock) — used in stock queries + POS
        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'track_stock'], 'products_active_track_stock_index');
        });

        // sale_payments: index sale_id — FK exists but no explicit index for joins
        Schema::table('sale_payments', function (Blueprint $table) {
            $table->index('sale_id', 'sale_payments_sale_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex('sales_status_sold_at_index');
        });

        Schema::table('sale_refunds', function (Blueprint $table) {
            $table->dropIndex('sale_refunds_status_refunded_at_index');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropIndex('purchases_status_purchased_at_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_active_track_stock_index');
        });

        Schema::table('sale_payments', function (Blueprint $table) {
            $table->dropIndex('sale_payments_sale_id_index');
        });
    }
};
