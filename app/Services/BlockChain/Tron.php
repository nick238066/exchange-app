<?php

namespace App\Services\BlockChain;

use GuzzleHttp\Client;
use App\Repositories\ExchangeAddressTypeRepository;
use Exception;
use Illuminate\Support\Facades\Storage;

class Tron implements BlockChainInterface
{
    public function createAccount($code, $user)
    {
        // 取得錢包地址類型
        $exchangeAddressTypeRepo = new ExchangeAddressTypeRepository;
        $address_type = $exchangeAddressTypeRepo->getExchangeAddressTypeByCode($code);

        // 取得環境值
        $env_setting = $address_type->env_setting;
        if ($env_setting) {
            $domain = $env_setting->domain;
            $url = $domain . "/wallet/generateaddress";

            $params = [];
            $result = $this->call($params, $url);

            # 紀錄tron address資訊，path: storage/app/tron
            $dir_path = 'tron';
            if (!is_dir(Storage::disk('local')->getAdapter()->getPathPrefix() . $dir_path)) {
                Storage::makeDirectory($dir_path, 0775, true); //creates directory
            }
            Storage::disk('local')->put($dir_path.'/'.$result['address'].'.txt', json_encode($result));

            return [
                "address" => $result['address'],
                "private_key" => $result['privateKey'],
            ];
        }

        return false;
    }

    protected function call($params, $url)
    {
        try {
            $headers = [
                'Content-Type' => 'application/json',
            ];

            $client = new Client();
            $response = $client->request("POST", $url, 
                [ 
                    'body' => json_encode($params),
                    'headers' => $headers, 
                ]
            );

            $result = json_decode($response->getBody(), true);
            return $result;

        } catch (Exception $e) {
            // telegram(
            //     'app name: ' . config('app.name') . "\n"
            //     . __METHOD__ . "\n"
            //     . 'message: ' . "Tron呼叫錯誤\n"
            //     . $e->getMessage() . "\n"
            // );
            dd("Tron呼叫錯誤");
        }
        return false;
    }
}