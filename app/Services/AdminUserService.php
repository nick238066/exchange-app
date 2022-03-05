<?php

namespace App\Services;

use Illuminate\Support\Arr;
use App\Models\ExchangeAddressType;
use App\Repositories\ExchangeAddressTypeRepository;
use App\Repositories\MemberAddressRepository;
use App\Services\BlockChain\BlockChainService;

class AdminUserService
{
    public function createUserExchangeAddress($user)
    {
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
                $address_info = $blockChainService->createAddress($code, $user);
            }
            dd($active_chains);
        }
    }
}