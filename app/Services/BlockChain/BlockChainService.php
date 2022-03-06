<?php

namespace App\Services\BlockChain;

class BlockChainService
{
    public function createAccount($code, $user)
    {
        $factory = ConcreteFactory::create($code);
        return $factory->createAccount($code, $user);
    }
}