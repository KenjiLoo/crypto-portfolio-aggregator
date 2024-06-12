<?php

namespace App\Helpers;

use phpseclib3\Crypt\AES;
use phpseclib3\Exception\BadDecryptionException;

class EncryptionHelper
{
    public static function encrypt(string $encrypt, bool $tolower = true, ?string $key = null): string
    {
        if ($tolower) {
            $encrypt = strtolower($encrypt);
        }

        $method = "AES-256-CBC";
        $encrypt = self::pad($encrypt, 16);
        $iv = substr($key, 16, 16);

        $encryption = openssl_encrypt(
            $encrypt,
            $method,
            $key,
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            $iv
        );

        return base64_encode($encryption);
    }

    public static function encryptCTR(string $encrypt, bool $tolower = true, ?string $key = null): string
    {
        if ($tolower) {
            $encrypt = strtolower($encrypt);
        }

        $method = "AES-128-CTR";
        $iv = substr($key, 16, 16);

        $encryption = openssl_encrypt(
            $encrypt,
            $method,
            $key,
            0,
            $iv
        );

        return $encryption;
    }

    public static function decrypt(string $decrypt, ?string $key = null): string
    {
        try {
            $iv = substr($key, 16, 16);
            $aesObj = new AES('cbc');
            $aesObj->setKey($key);
            $aesObj->setKeyLength(256);
            $aesObj->setIV($iv);
            $decrypted = $aesObj->decrypt(base64_decode($decrypt));

            return trim($decrypted);
        } catch (BadDecryptionException $e) {
            $aesObj->disablePadding();
            $decrypted = $aesObj->decrypt(base64_decode($decrypt));

            return trim($decrypted);
        }

        return '';
    }

    public static function decryptCTR(string $decrypt, ?string $key = null): string
    {
        $method = 'AES-128-CTR';
        $iv = substr($key, 16, 16);

        $decryption = openssl_decrypt(
            $decrypt,
            $method,
            $key,
            0,
            $iv
        );

        return trim($decryption);
    }

    private static function pad(string $encrypt, int $blockSize): string
    {
        $encryptLength = strlen($encrypt);
        $padLength = $blockSize - ($encryptLength % $blockSize);
        if ($padLength == 0) {
            $padLength = $blockSize;
        }

        $padChar = chr(0);
        $tmp = '';
        for ($index = 0; $index < $padLength; $index++) {
            $tmp .= $padChar;
        }

        return $encrypt . $tmp;
    }
}
