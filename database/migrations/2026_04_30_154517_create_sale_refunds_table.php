<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_refunds', function (Blueprint $table) {
            $table->id();

            $table->string('refund_number')->unique();

            $table->foreignId('sale_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('method', 50)->default('cash');
            $table->string('status', 50)->default('completed');
            $table->text('reason')->nullable();
            $table->timestamp('refunded_at')->nullable();

            $table->timestamps();

            $table->index('refund_number');
            $table->index('status');
            $table->index('refunded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_refunds');
    }
};