<?php

namespace App\Services\BlockChain;

interface BlockChainInterface
{
    public function createAccount($code, $user);
}