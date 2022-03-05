<?php

namespace App\Services\BlockChain;

class BlockChainService
{
    public function createAddress($code, $user)
    {
        $factory = ConcreteFactory::create($code);
        return $factory->createAddress($user);
    }
}