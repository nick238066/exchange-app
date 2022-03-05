<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartContract extends Model
{
    use HasFactory;

    public function env_setting()
    {
        return $this->belongsTo(EnvSetting::class);
    }

    public function address_type()
    {
        return $this->belongsTo(ExchangeAddressType::class, 'address_type_id', 'id');
    }
}
