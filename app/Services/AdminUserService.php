<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use App\Models\ExchangeAddressType;
use App\Repositories\ExchangeAddressTypeRepository;
use App\Repositories\MemberAddressRepository;
use App\Services\BlockChain\BlockChainService;

class AdminUserService
{
    public function createUserExchangeAddress($user)
    {
        $add_count = 0;
        // $active_chains = Arr::except(ExchangeAddressType::ADDRESS_TYPES, [ExchangeAddressType::TYPES_OMNI]);
        $active_chains = Arr::only(ExchangeAddressType::ADDRESS_TYPES, [ExchangeAddressType::TYPES_TRC20]);
        if (count($active_chains) > 0) {
            $exchangeAddressTypeRepo = new ExchangeAddressTypeRepository;
            $memberAddressRepo = new MemberAddressRepository;
            $blockChainService = new BlockChainService;
            foreach ($active_chains as $code => $address_name) {
                // 取得地址類型
                $address_type = $exchangeAddressTypeRepo->getExchangeAddressTypeByCode($code);
                if (empty($address_type)) {
                    continue;
                }

                // 檢查會員錢包地址
                $member_address = $memberAddressRepo->getMemberAddress([
                    'user_id' => $user->id,
                    'address_type_id' => $address_type->id,
                ]);
                if ($member_address->count()) {
                    continue;
                }

                // 新增錢包地址
                $account_info = $blockChainService->createAccount($code, $user);
                if (isset($account_info['address']) && empty($account_info['address'])) {
                    continue;
                }

                // 新增區塊練地址
                $account_info['private_key'] = isset($account_info['private_key']) ? Crypt::encryptString($account_info['private_key']) : null;
                $wallet_address = $address_type->wallet_addresses()->create($account_info);

                // 新增會員與區塊練地址紀錄
                $member_info = ['user_id' => $user->id, 'address_type_id' => $wallet_address->id];
                $member_address = $wallet_address->member_address()->create($member_info);

                $add_count++;
            }
        }

        return $add_count;
    }
}