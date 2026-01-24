<?php

if (!function_exists('dataEncrypt')) {
    function dataEncrypt($string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'SecreT1234';
        $secret_iv = 'IV1234';
        // hash
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }
}
if (!function_exists('dataDecrypt')) {
    function dataDecrypt($string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'SecreT1234';
        $secret_iv = 'IV1234';
        // hash
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        return $output;
    }
}
