<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberAddressToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_type_id',
        'address_id',
        'contract_id',
        'currency_id',
        'balance',
    ];
}
