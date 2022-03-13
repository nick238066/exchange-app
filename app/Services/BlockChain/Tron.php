<?php

namespace App\Services\BlockChain;

use GuzzleHttp\Client;
use App\Repositories\ExchangeAddressTypeRepository;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Arr;
use App\Services\Sdk\TronSdk;

class Tron implements BlockChainInterface
{
    protected $address_type;
    protected $env_setting;

    public function setAddressType($code)
    {
        // 取得錢包地址類型
        $exchangeAddressTypeRepo = new ExchangeAddressTypeRepository;
        $this->address_type = $exchangeAddressTypeRepo->getExchangeAddressTypeByCode($code);

        // 取得環境值
        $this->env_setting = $this->address_type->env_setting;
    }

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

    public function getBalance($code, $address, $contract_token = null)
    {
        // 取得錢包地址類型
        $this->setAddressType($code);

        if ($this->env_setting) {
            $domain = $this->env_setting->domain;

            if ($contract_token == '-') {
                $url = $domain . "/wallet/getaccount";

                $params = [
                    'address' => $address,
                    'visible' => true,
                ];
            } else {
                $url = $domain . "/wallet/triggerconstantcontract";

                $encodeParams = [
                    'address' => str_pad(App::call(TronSdk::class . '@base58check2HexString', ['str' => $address]), 64, "0", STR_PAD_LEFT),
                ];

                $params = [
                    'contract_address' => $contract_token,
                    'function_selector' => 'balanceOf(address)',            
                    'parameter' => $encodeParams['address'],
                    'owner_address' => $address,
                    'visible' => true,
                ];
            }
            
            $result = $this->call($params, $url);

            return $result;
        }

        throw new Exception("查無錢包地址環境設定");
    }

    public function getAmount($value, $decimals, $convert=false)
    {
        if ($convert) {
            $value = base_convert($value, 16, 10);
        }
        return $value/pow(10, $decimals);
    }

    public function getConvertBalanceResult($balance_result, $contract_token, $decimals, $convert=false)
    {
        if ($contract_token == '-') {
            $value = Arr::get($balance_result, 'balance', false);
            if (!$value) {
                throw new Exception("查無錢包地址合約餘額");
            }
            $convert = false; // 不轉換
        } else {
            $value = Arr::get($balance_result, 'result', false);
            if (is_array($value) && (isset($value['result']) && $value['result'] == true)) {
                $value = App::call(TronSdk::class . '@getAbiDecode', ['param' => $balance_result['constant_result'][0], 'type' => 'balanceOf']);
                $value = $value[0]->toHex();
            } else {
                throw new Exception("查無錢包地址合約餘額");
            }
        }

        return $this->getAmount($value, $decimals, $convert);
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