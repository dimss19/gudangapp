<?php

namespace App\Enums;
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