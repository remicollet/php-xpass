--TEST--
Test crypt_checksalt
--FILE--
<?php
// salt with old algo is OK or LEGACY
$r = crypt_checksalt(crypt_gensalt(XPASS_CRYPT_STD_DES));
var_dump($r === CRYPT_SALT_METHOD_LEGACY || $r === CRYPT_SALT_OK);

// salt with default algo is OK
$r = crypt_checksalt(crypt_gensalt());
var_dump($r === CRYPT_SALT_OK);

// bad salt is INVALID
$r = crypt_checksalt("!not_a_valid_hash");
var_dump($r === CRYPT_SALT_INVALID);
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
