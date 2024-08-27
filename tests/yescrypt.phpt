--TEST--
Check if xpass is loaded
--EXTENSIONS--
xpass
--SKIPIF--
<?php
if (!defined("PASSWORD_YESCRYPT")) die("skip YESCRYPT missing");
?>
--FILE--
<?php
$data = [
	'mysecret'   => '$y$j9T$EWkxmhFdtlCH.UrDi8l6T1$65TpODO1HXFLut3PhZhxiFweWNWFpo7QHTQtMVanr2B',
	'remicollet' => '$y$j9T$XxuuhBKq0UT68HX8KXaXy0$p.PggRtVfQ6rO5TReD0TgMKFyfNEA2l3QQi/dW8fS63',
];
foreach($data as $pass => $hash) {
	echo "-- $pass\n";
	var_dump(password_verify($pass, $hash));
	var_dump(password_get_info($hash));
	var_dump(password_verify($pass."bad", $hash));
	var_dump(password_verify($pass, $hash."bad"));
	var_dump(password_needs_rehash($hash, PASSWORD_YESCRYPT));
}

echo "-- no cost\n";
$pass = 'secret';
var_dump($hash = password_hash($pass, PASSWORD_YESCRYPT));
var_dump(password_get_info($hash));
var_dump(password_verify($pass, $hash));
foreach([0,4,8,99] as $cost) {
	echo "-- cost=$cost\n";
	try {
		$pass = "secret$cost";
		var_dump($hash = password_hash($pass, PASSWORD_YESCRYPT, ['cost'=>$cost]));
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
  string(1) "y"
  ["algoName"]=>
  string(8) "yescrypt"
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
  string(1) "y"
  ["algoName"]=>
  string(8) "yescrypt"
  ["options"]=>
  array(0) {
  }
}
bool(false)
bool(false)
bool(false)
-- no cost
string(73) "$y$j9T$%s"
array(3) {
  ["algo"]=>
  string(1) "y"
  ["algoName"]=>
  string(8) "yescrypt"
  ["options"]=>
  array(0) {
  }
}
bool(true)
-- cost=0
string(73) "$y$j9T$%s"
bool(true)
-- cost=4
string(73) "$y$j8T$%s"
bool(true)
-- cost=8
string(73) "$y$jCT$%s"
bool(true)
-- cost=99
EXCEPTION Bad password options

