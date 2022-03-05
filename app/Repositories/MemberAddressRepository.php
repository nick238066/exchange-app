<?php

namespace App\Repositories;

use App\Models\MemberAddress;

class MemberAddressRepository
{
    public function getMemberAddress($where)
    {
        return MemberAddress::where($where)->get();
    }
}