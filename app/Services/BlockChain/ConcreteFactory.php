<?php

namespace App\Services\BlockChain;

use App\Models\ExchangeAddressType;

class ConcreteFactory
{
    public static function create(string $code)
    {
        switch ($code) {
            case ExchangeAddressType::TYPES_TRC20:
                return new Tron;
                break;
            default:
                dd('error');
        }
    }
}