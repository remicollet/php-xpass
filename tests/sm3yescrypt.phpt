--TEST--
Check sm3yescrypt algo
--EXTENSIONS--
xpass
--SKIPIF--
<?php
if (!defined("PASSWORD_SM3_YESCRYPT")) die("skip SM3_YESCRYPT missing");
?>
--FILE--
<?php
$data = [
	'mysecret'   => '$sm3y$j9T$FeOt/DFtz1Mm8/mtgIer2.$MtA20j5f5L.Jz8MM4KuKCFgPstmpnswmC2/9BEPwND.',
	'remicollet' => '$sm3y$j9T$ElHlNepC6e7htB1BgBbe4.$VXOJS4j0v49cNKDN/6i66F4nwvTCUT5GsPwqKEewSI/',
];
foreach($data as $pass => $hash) {
	echo "-- $pass\n";
	var_dump(password_verify($pass, $hash));
	var_dump(password_get_info($hash));
	var_dump(password_verify($pass."bad", $hash));
	var_dump(password_verify($pass, $hash."bad"));
	var_dump(password_needs_rehash($hash, PASSWORD_SM3_YESCRYPT));
}

echo "-- no cost\n";
$pass = 'secret';
var_dump($hash = password_hash($pass, PASSWORD_SM3_YESCRYPT));
var_dump(password_get_info($hash));
var_dump(password_verify($pass, $hash));
?>
--EXPECTF--
-- mysecret
bool(true)
array(3) {
  ["algo"]=>
  string(4) "sm3y"
  ["algoName"]=>
  string(11) "sm3yescrypt"
  ["options"]=>
  array(0) {
  }
}
bool(false)
bool(false)
bool(false)
-- remicollet
bool(true)
array(3) {
  ["algo"]=>
  string(4) "sm3y"
  ["algoName"]=>
  string(11) "sm3yescrypt"
  ["options"]=>
  array(0) {
  }
}
bool(false)
bool(false)
bool(false)
-- no cost
string(76) "$sm3y$%s"
array(3) {
  ["algo"]=>
  string(4) "sm3y"
  ["algoName"]=>
  string(11) "sm3yescrypt"
  ["options"]=>
  array(0) {
  }
}
bool(true)

