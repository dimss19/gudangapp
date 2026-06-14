<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Warehouse;
use App\Models\Category;
use App\Models\Supplier;
use App\Enums\RoleEnum;
use App\Enums\WarehouseTypeEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        User::create([
            'name' => 'Admin',
            'email' => 'admin@warehouse.com',
            'password' => Hash::make('password'),
            'role' => RoleEnum::ADMIN,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Staff Gudang',
            'email' => 'staff@warehouse.com',
            'password' => Hash::make('password'),
            'role' => RoleEnum::STAFF,
            'is_active' => true,
        ]);

        // Warehouses
        Warehouse::create([
            'name' => 'Gudang Pusat',
            'code' => 'WH-001',
            'type' => WarehouseTypeEnum::MAIN,
            'address' => 'Jl. Industri No. 1, Jakarta',
            'phone' => '021-12345678',
            'is_active' => true,
        ]);

        Warehouse::create([
            'name' => 'Gudang Cabang Bandung',
            'code' => 'WH-002',
            'type' => WarehouseTypeEnum::BRANCH,
            'address' => 'Jl. Soekarno Hatta No. 45, Bandung',
            'phone' => '022-87654321',
            'is_active' => true,
        ]);

        Warehouse::create([
            'name' => 'Gudang Retur',
            'code' => 'WH-RET',
            'type' => WarehouseTypeEnum::RETURN,
            'address' => 'Jl. Logistik No. 99, Jakarta',
            'phone' => '021-99887766',
            'is_active' => true,
        ]);

        // Categories
        foreach ([
            ['name' => 'Elektronik', 'slug' => 'elektronik', 'description' => 'Produk elektronik'],
            ['name' => 'Furniture', 'slug' => 'furniture', 'description' => 'Produk furniture'],
            ['name' => 'Alat Tulis', 'slug' => 'alat-tulis', 'description' => 'Alat tulis kantor'],
            ['name' => 'Makanan', 'slug' => 'makanan', 'description' => 'Produk makanan'],
        ] as $category) {
            Category::create($category);
        }

        // Suppliers
        foreach ([
            [
                'name' => 'PT Elektronik Jaya',
                'code' => 'SUP-001',
                'email' => 'elektronik@example.com',
                'phone' => '021-11111111',
                'address' => 'Jakarta',
                'is_active' => true,
            ],
            [
                'name' => 'CV Furniture Indo',
                'code' => 'SUP-002',
                'email' => 'furniture@example.com',
                'phone' => '021-22222222',
                'address' => 'Bandung',
                'is_active' => true,
            ],
            [
                'name' => 'Toko Alat Tulis Makmur',
                'code' => 'SUP-003',
                'email' => 'alattulis@example.com',
                'phone' => '021-33333333',
                'address' => 'Surabaya',
                'is_active' => true,
            ],
        ] as $supplier) {
            Supplier::create($supplier);
        }

        $this->command->info('Seeding completed successfully!');
        $this->command->info('Admin: admin@warehouse.com');
        $this->command->info('Staff: staff@warehouse.com');
        $this->command->info('Password: password');
    }
}
