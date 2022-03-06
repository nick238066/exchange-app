<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeAddressType extends Model
{
    use HasFactory;

    public const TYPES_ERC20 = 'erc20_address';
    public const TYPES_TRC20 = 'trc20_address';

    public const ADDRESS_TYPES = [
        self::TYPES_ERC20 => 'ETH-ERC20',
        self::TYPES_TRC20 => 'TRX-TRC20',
    ];

    public function env_setting()
    {
        return $this->belongsTo(EnvSetting::class);
    }

    public function wallet_addresses()
    {
        return $this->hasMany(WalletAddress::class, 'address_type_id', 'id');
    }
}
