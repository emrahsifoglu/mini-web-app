<?php

function decryptRJ256($key,$iv,$string_to_decrypt) {
    $string_to_decrypt = base64_decode($string_to_decrypt);
    $rtn = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $string_to_decrypt, MCRYPT_MODE_CBC, $iv);
    $rtn = rtrim($rtn, "\0\4");
    return($rtn);
}

function encryptRJ256($key,$iv,$string_to_encrypt) {
    $rtn = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string_to_encrypt, MCRYPT_MODE_CBC, $iv);
    $rtn = base64_encode($rtn);
    return($rtn);
}