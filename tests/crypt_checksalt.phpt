--TEST--
Test crypt_checksalt
--FILE--
<?php
var_dump(crypt_checksalt(crypt_gensalt(XPASS_CRYPT_STD_DES)) === CRYPT_SALT_METHOD_LEGACY);
var_dump(crypt_checksalt(crypt_gensalt()) === CRYPT_SALT_OK);
var_dump(crypt_checksalt("!not_a_valid_hash") === CRYPT_SALT_INVALID);
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
