<?php

namespace App\Services;

use Exception;
use App\Services\BlockChain\BlockChainService;
use App\Services\MemberAddressTokenService;
use App\Repositories\MemberAddressTokenRepository;

class WalletAddressService
{
    /**
     * 查詢錢包地址餘額
     */
    public function getExchangeWalletBalance($address, $contract_token = null)
    {
        // 檢查地址
        if(!$address){
            throw new Exception("查無地址");
        }

        // 檢查地址類型
        $address_type = $address->address_type;
        if (!$address_type) {
            throw new Exception("查無地址類型");
        }

        // 檢查合約地址設定
        $smart_contract = null;
        if ($contract_token) {
            $smart_contract = $address_type->smart_contracts()->where('token', $contract_token)->first();
            if (!$smart_contract) {
                throw new Exception("查無合約地址設定");
            }
        }

        // 查詢錢包地址餘額
        $blockChainService = new BlockChainService;
        $balance_result = $blockChainService->getBalance($address_type->code, $address->address, $contract_token);
        $value = $blockChainService->getConvertBalanceResult($address_type->code, $balance_result, $contract_token, $smart_contract->decimals, true);

        // 查詢使用者與錢包地址綁定
        $member_address = $address->member_address;
        if (!$member_address) {
            throw new Exception("使用者與錢包地址綁定");
        }

        $memberAddressTokenRepo = new MemberAddressTokenRepository;
        $member_address_token = $memberAddressTokenRepo->updateOrCreate([
            'user_id' => $member_address->user_id,
            'address_type_id' => $member_address->address_type_id,
            'address_id' => $member_address->address_id,
            'contract_id' => $smart_contract->id,
            'currency_id' => $smart_contract->currency_id,
        ], [
            'balance' => $value,
        ]);

        if ($member_address_token) {
            return $member_address_token;
        }

        throw new Exception("查詢錢包地址合約餘額失敗");
    }
}