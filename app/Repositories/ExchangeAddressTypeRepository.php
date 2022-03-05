<?php

namespace App\Repositories;

use App\Models\ExchangeAddressType;

class ExchangeAddressTypeRepository
{
    public function getExchangeAddressTypeByCode($code)
    {
        return ExchangeAddressType::where('code', $code)->get()->first();
    }
}