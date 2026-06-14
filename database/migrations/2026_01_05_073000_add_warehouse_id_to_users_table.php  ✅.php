<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan foreign key untuk kolom `warehouse_id` yang sudah ada
            // dan pastikan merujuk ke tabel `warehouses` yang sudah dibuat.
            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign key yang ditambahkan pada metode up
            $table->dropForeign(['warehouse_id']);
        });

    }
};
