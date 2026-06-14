<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case STAFF = 'staff';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::STAFF => 'Staff Gudang',
        };
    }
}

enum WarehouseTypeEnum: string
{
    case MAIN = 'main';
    case BRANCH = 'branch';
    case RETURN = 'return';

    public function label(): string
    {
        return match($this) {
            self::MAIN => 'Gudang Utama',
            self::BRANCH => 'Gudang Cabang',
            self::RETURN => 'Gudang Retur',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::MAIN => 'blue',
            self::BRANCH => 'green',
            self::RETURN => 'yellow',
        };
    }
}

enum MovementTypeEnum: string
{
    case IN = 'in';
    case OUT = 'out';
    case TRANSFER_IN = 'transfer_in';
    case TRANSFER_OUT = 'transfer_out';
    case ADJUSTMENT = 'adjustment';
    case RETURN = 'return';

    public function label(): string
    {
        return match($this) {
            self::IN => 'Barang Masuk',
            self::OUT => 'Barang Keluar',
            self::TRANSFER_IN => 'Transfer Masuk',
            self::TRANSFER_OUT => 'Transfer Keluar',
            self::ADJUSTMENT => 'Penyesuaian',
            self::RETURN => 'Retur',
        };
    }

    public function isPositive(): bool
    {
        return in_array($this, [self::IN, self::TRANSFER_IN, self::RETURN]);
    }

    public function color(): string
    {
        return $this->isPositive() ? 'green' : 'red';
    }
}