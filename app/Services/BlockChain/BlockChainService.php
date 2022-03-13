<?php

namespace App\Services\BlockChain;

class BlockChainService
{
    public function createAccount($code, $user)
    {
        $factory = ConcreteFactory::create($code);
        return $factory->createAccount($code, $user);
    }

    public function getBalance($code, $address, $contract_token = null)
    {
        $factory = ConcreteFactory::create($code);
        return $factory->getBalance($code, $address, $contract_token);
    }

    public function getConvertBalanceResult($code, $balance_result, $contract_token, $decimals, $convert=false)
    {
        $factory = ConcreteFactory::create($code);
        return $factory->getConvertBalanceResult($balance_result, $contract_token, $decimals, $convert);
    }
}