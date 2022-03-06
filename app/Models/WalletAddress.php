<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'address_type_id',
        'address',
        'private_key',
    ];

    public function member_address()
    {
        return $this->hasOne(MemberAddress::class, 'address_id', 'id');
    }
}
