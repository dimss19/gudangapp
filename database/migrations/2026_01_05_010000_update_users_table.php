<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom warehouse_id tanpa constraint terlebih dahulu.
            // Constraint foreign key akan ditangani di migration berikutnya
            // setelah tabel warehouses dibuat.
            $table->unsignedBigInteger('warehouse_id')
                ->nullable()
                ->after('role');

            // Index untuk performa pada kolom baru
            $table->index('warehouse_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom dan index yang ditambahkan pada metode up
            $table->dropIndex(['warehouse_id']);
            $table->dropColumn('warehouse_id');
        });

    }
};
