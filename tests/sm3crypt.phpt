--TEST--
Check sm3crypt algo
--EXTENSIONS--
xpass
--SKIPIF--
<?php
if (!defined("PASSWORD_SM3CRYPT")) die("skip SM3CRYPT missing");
?>
--FILE--
<?php
$data = [
	'mysecret'   => '$sm3$wjwOAHL3D6DzuOsN$QF8mPXc8WfTfOE1PsWw05FgUogEEWqE5jh/ZlhCFof.',
	'remicollet' => '$sm3$L/iCXvrL76KHTQBh$Jj0jcZoeFgH7wb3Tepl6yiVhL4Pm5Kd6GcInazUcOs.',
];
foreach($data as $pass => $hash) {
	echo "-- $pass\n";
	var_dump(password_verify($pass, $hash));
	var_dump(password_get_info($hash));
	var_dump(password_verify($pass."bad", $hash));
	var_dump(password_verify($pass, $hash."bad"));
	var_dump(password_needs_rehash($hash, PASSWORD_SM3CRYPT));
}

echo "-- no cost\n";
$pass = 'secret';
var_dump($hash = password_hash($pass, PASSWORD_SM3CRYPT));
var_dump(password_get_info($hash));
var_dump(password_verify($pass, $hash));
?>
--EXPECTF--
-- mysecret
bool(true)
array(3) {
  ["algo"]=>
  string(3) "sm3"
  ["algoName"]=>
  string(8) "sm3crypt"
  ["options"]=>
  array(0) {
  }
}
bool(false)
bool(false)
bool(true)
-- remicollet
bool(true)
array(3) {
  ["algo"]=>
  string(3) "sm3"
  ["algoName"]=>
  string(8) "sm3crypt"
  ["options"]=>
  array(0) {
  }
}
bool(false)
bool(false)
bool(true)
-- no cost
string(65) "$sm3$%s"
array(3) {
  ["algo"]=>
  string(3) "sm3"
  ["algoName"]=>
  string(8) "sm3crypt"
  ["options"]=>
  array(0) {
  }
}
bool(true)

