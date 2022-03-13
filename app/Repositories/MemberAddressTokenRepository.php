<?php

namespace App\Repositories;

use App\Models\MemberAddressToken;

class MemberAddressTokenRepository
{
    public function updateOrCreate($where, $data = [])
    {
        $memberAddressToken = MemberAddressToken::updateOrCreate(
            $where,
            $data
        );

        return $memberAddressToken;
    }
}