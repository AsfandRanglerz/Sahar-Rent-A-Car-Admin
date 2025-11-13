<?php

namespace App\Helpers;

class CCAvenue
{
    public static function encrypt($plainText, $workingKey)
    {
        $key = pack('H*', md5($workingKey));
        $iv = $key;
        return bin2hex(openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv));
    }

    public static function decrypt($encryptedText, $workingKey)
    {
        $key = pack('H*', md5($workingKey));
        $iv = $key;
        return openssl_decrypt(hex2bin($encryptedText), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }
}
