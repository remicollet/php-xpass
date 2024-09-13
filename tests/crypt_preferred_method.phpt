--TEST--
Test crypt_preferred_method
--FILE--
<?php
var_dump(crypt_preferred_method());
?>
--EXPECTF--
string(%d) "$%s$"

