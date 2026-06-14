<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('warehouse_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->restrictOnDelete();

            // Tipe pergerakan stok
            $table->enum('type', ['in', 'out']);

            // Jumlah SELALU POSITIF
            $table->decimal('quantity', 15, 2);

            // Harga saat transaksi (opsional)
            $table->decimal('price', 15, 2)->nullable();

            // Referensi transaksi (purchase, sales, transfer, adjustment)
            $table->string('reference_type', 50)->nullable();
            $table->string('reference_id', 100)->nullable();

            $table->text('notes')->nullable();
            $table->timestamp('movement_date')->useCurrent();

            $table->timestamps();
            $table->softDeletes();

            // Index untuk performa
            $table->index(['product_id', 'warehouse_id', 'movement_date']);
            $table->index(['warehouse_id', 'type']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
