--TEST--
Test crypt_gensalt
--FILE--
<?php
var_dump(crypt_gensalt(CRYPT_PREFIX_MD5));
var_dump(crypt_gensalt(CRYPT_PREFIX_BLOWFISH));
var_dump(crypt_gensalt(CRYPT_PREFIX_SHA256));
var_dump(crypt_gensalt(CRYPT_PREFIX_SHA512));
var_dump(crypt_gensalt(CRYPT_PREFIX_SCRYPT));
var_dump(crypt_gensalt(CRYPT_PREFIX_GOST_YESCRYPT));
var_dump(crypt_gensalt(CRYPT_PREFIX_YESCRYPT));

?>
--EXPECTF--
string(11) "$1$%s"
string(29) "$2y$%s"
string(19) "$5$%s"
string(19) "$6$%s"
string(36) "$7$%s"
string(30) "$gy$%s"
string(29) "$y$j%s"
