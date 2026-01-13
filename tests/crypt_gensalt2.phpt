--TEST--
Test crypt_gensalt
--SKIPIF--
<?php
if (!defined("CRYPT_PREFIX_SM3CRYPT")) die("skip SM3CRYPT missing");
?>
--FILE--
<?php
var_dump(crypt_gensalt(CRYPT_PREFIX_SM3CRYPT));
var_dump(crypt_gensalt(CRYPT_PREFIX_SM3_YESCRYPT));

?>
--EXPECTF--
string(21) "$sm3$%s"
string(32) "$sm3y$%s"
