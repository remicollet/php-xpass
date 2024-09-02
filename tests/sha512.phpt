--TEST--
Check sha512 algo
--EXTENSIONS--
xpass
--SKIPIF--
<?php
if (!defined("PASSWORD_SHA512")) die("skip SHA512 missing");
?>
--FILE--
<?php
$data = [
	'mysecret'   => '$6$1w/SLyhyEvGAul3q$W5VyKdQFPZaNOoIWMJTIi590Tu7ioejI90F0asQneP/Mn893X0m3aPIb2J7I4cPXtuN65t/vNwgMGHlfvf6hK/',
	'remicollet' => '$6$BG/h0.OlUBaXdi11$iJnP3HmoR3QicxajlNTgGPpLBEDAe/QTpcrNPPZJwcc.orIwvTPQK5E5IjPmIu2ArLj3mjjVGDUSRNgDb32jD.',
];
foreach($data as $pass => $hash) {
	echo "-- $pass\n";
	var_dump(password_verify($pass, $hash));
	var_dump(password_get_info($hash));
	var_dump(password_verify($pass."bad", $hash));
	var_dump(password_verify($pass, $hash."bad"));
	var_dump(password_needs_rehash($hash, PASSWORD_SHA512));
}

echo "-- no cost\n";
$pass = 'secret';
var_dump($hash = password_hash($pass, PASSWORD_SHA512));
var_dump(password_get_info($hash));
var_dump(password_verify($pass, $hash));
foreach([0,4,8,99] as $cost) {
	echo "-- cost=$cost\n";
	try {
		$pass = "secret$cost";
		var_dump($hash = password_hash($pass, PASSWORD_SHA512, ['cost'=>$cost]));
		var_dump(password_verify($pass, $hash));
	} catch (ValueError $e) {
		printf("EXCEPTION %s\n", $e->getMessage());
	}
}
?>
--EXPECTF--
-- mysecret
bool(true)
array(3) {
  ["algo"]=>
  string(1) "6"
  ["algoName"]=>
  string(6) "sha512"
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
  string(1) "6"
  ["algoName"]=>
  string(6) "sha512"
  ["options"]=>
  array(0) {
  }
}
bool(false)
bool(false)
bool(false)
-- no cost
string(106) "$6$%s"
array(3) {
  ["algo"]=>
  string(1) "6"
  ["algoName"]=>
  string(6) "sha512"
  ["options"]=>
  array(0) {
  }
}
bool(true)
-- cost=0
string(106) "$6$%s"
bool(true)
-- cost=4
string(118) "$6$rounds=1000$%s"
bool(true)
-- cost=8
string(118) "$6$rounds=1000$%s"
bool(true)
-- cost=99
string(118) "$6$rounds=1000$%s"
bool(true)

