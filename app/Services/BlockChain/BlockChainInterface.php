<?php

namespace App\Services\BlockChain;

interface BlockChainInterface
{
    public function createAccount($code, $user);

    public function getBalance($code, $address, $contract_token = null);

    public function getAmount($value, $decimals, $convert=false);

    public function getConvertBalanceResult($balance_result, $contract_token, $decimals, $convert=false);
}