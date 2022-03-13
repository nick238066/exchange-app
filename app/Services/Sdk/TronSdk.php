<?php

namespace App\Services\Sdk;

// Web3 plugin
use Web3\Contracts\Ethabi;
use Web3\Contracts\Types\{Address, Boolean, Bytes, DynamicBytes, Integer, Str, Uinteger};
use Exception;

class TronSdk
{
    public function getAbiEncode($params, $type='transfer')
    {
        $eth_abi = new Ethabi([
            'address' => new Address,
            'bool' => new Boolean,
            'bytes' => new Bytes,
            'dynamicBytes' => new DynamicBytes,
            'int' => new Integer,
            'string' => new Str,
            'uint' => new Uinteger,
        ]);

        $func_abi = $this->getFuncAbi($type);
        return substr($eth_abi->encodeParameters($func_abi, $params),2);
    }
    
    public function getAbiDecode($param, $type='transfer')
    {
        $eth_abi = new Ethabi([
            'address' => new Address,
            'bool' => new Boolean,
            'bytes' => new Bytes,
            'dynamicBytes' => new DynamicBytes,
            'int' => new Integer,
            'string' => new Str,
            'uint' => new Uinteger,
        ]);

        $func_abi = $this->getFuncAbi($type);
        return $eth_abi->decodeParameters($func_abi, $param);
    }
    
    public function getFuncAbi($type)
    {
        $func_abi = [];
        if ($type == 'transfer') {
            # 轉帳
            $func_abi['outputs'] = [
                // ['type' => 'bool'],
                ['name' => 'dst', 'type' => 'address'],
                ['name' => 'wad', 'type' => 'uint256']
            ];
            $func_abi['inputs'] = [
                ['name' => 'dst', 'type' => 'address'],
                ['name' => 'wad', 'type' => 'uint256'],
            ];
            $func_abi['name'] = 'transfer';
            $func_abi['stateMutability'] = 'Nonpayable';
            $func_abi['type'] = 'Function';

        } elseif ($type == 'balanceOf') {
            # 代幣餘額
            $func_abi['outputs'] = [
                ['type' => 'uint256'],
            ];
            $func_abi['constant'] = true;
            $func_abi['inputs'] = [
                ['name' => 'src', 'type' => 'address'],
            ];
            $func_abi['name'] = 'balanceOf';
            $func_abi['stateMutability'] = 'View';
            $func_abi['type'] = 'Function';

        }
        return $func_abi;
    }

    public function base58check2HexString($str){
        $address = $this->base58check_de($str);
        $hexString = bin2hex($address);
        return $hexString;
    }

    public function base58check_de($base58add)
    {
        $address = $this->base58_decode($base58add);
        $size = strlen($address);
        if ($size != 25) {
            return false;
        }
        $checksum = substr($address, 21);
        $address = substr($address, 0, 21);     
        $hash0 = hash("sha256", $address);
        $hash1 = hash("sha256", hex2bin($hash0));
        $checksum0 = substr($hash1, 0, 8);
        $checksum1 = bin2hex($checksum);
        if (strcmp($checksum0, $checksum1)) {
            return false;
        }
        return $address;
    }

    public function base58_decode($base58)
    {
        $alphabet = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
        $base = strlen($alphabet);
        if (is_string($base58) === false) {
            return false;
        }
        if (strlen($base58) === 0) {
            return '';
        }
        $indexes = array_flip(str_split($alphabet));
        $chars = str_split($base58);
        foreach ($chars as $char) {
            if (isset($indexes[$char]) === false) {
                return false;
            }
        }
        $decimal = $indexes[$chars[0]];
        for ($i = 1, $l = count($chars); $i < $l; $i++) {
            $decimal = bcmul($decimal, $base);
            $decimal = bcadd($decimal, $indexes[$chars[$i]]);
        }
        $output = '';
        while ($decimal > 0) {
            $byte = bcmod($decimal, 256);
            $output = pack('C', $byte) . $output;
            $decimal = bcdiv($decimal, 256, 0);
        }
        foreach ($chars as $char) {
            if ($indexes[$char] === 0) {
                $output = "\x00" . $output;
                continue;
            }
            break;
        }
        return $output;
    }

    public function hexString2Base58check($str)
    {
        $address = hex2bin($str);
        $base58add = $this->base58check_en($address);
        return $base58add;
    }

    function base58check_en($address)
    {
        $hash0 = hash("sha256", $address);
        $hash1 = hash("sha256", hex2bin($hash0));
        $checksum = substr($hash1, 0, 8);
        $address = $address.hex2bin($checksum);
        $base58add = $this->base58_encode($address);
        return $base58add;
    }

    function base58_encode($string)
    {
        $alphabet = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
        $base = strlen($alphabet);
        if (is_string($string) === false) {
            return false;
        }
        if (strlen($string) === 0) {
            return '';
        }
        $bytes = array_values(unpack('C*', $string));
        $decimal = $bytes[0];
        for ($i = 1, $l = count($bytes); $i < $l; $i++) {
            $decimal = bcmul($decimal, 256);
            $decimal = bcadd($decimal, $bytes[$i]);
        }
        $output = '';
        while ($decimal >= $base) {
            $div = bcdiv($decimal, $base, 0);
            $mod = bcmod($decimal, $base);
            $output .= $alphabet[$mod];
            $decimal = $div;
        }
        if ($decimal > 0) {
            $output .= $alphabet[$decimal];
        }
        $output = strrev($output);
        foreach ($bytes as $byte) {
            if ($byte === 0) {
                $output = $alphabet[0] . $output;
                continue;
            }
            break;
        }
        return (string) $output;
    }
}