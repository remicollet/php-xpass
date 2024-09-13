--TEST--
Test crypt_gensalt
--FILE--
<?php
var_dump(crypt_gensalt(XPASS_CRYPT_STD_DES));
var_dump(crypt_gensalt(XPASS_CRYPT_EXT_DES));
var_dump(crypt_gensalt(XPASS_CRYPT_MD5));
var_dump(crypt_gensalt(XPASS_CRYPT_BLOWFISH));
var_dump(crypt_gensalt(XPASS_CRYPT_SHA256));
var_dump(crypt_gensalt(XPASS_CRYPT_SHA512));
var_dump(crypt_gensalt(XPASS_CRYPT_SCRYPT));
var_dump(crypt_gensalt(XPASS_CRYPT_GOST_YESCRYPT));
var_dump(crypt_gensalt(XPASS_CRYPT_YESCRYPT));

?>
--EXPECTF--
string(2) "%s"
string(9) "_%s"
string(11) "$1$%s"
string(29) "$2y$%s"
string(19) "$5$%s"
string(19) "$6$%s"
string(36) "$7$%s"
string(30) "$gy$%s"
string(29) "$y$j%s"
