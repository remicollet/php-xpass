<?php

$deb = microtime(true);
echo 'PASSWORD_BCRYPT: ';
for($i=0; $i<10; $i++) {
  $h = password_hash("secret$i", PASSWORD_BCRYPT);
}
printf("%.3f\"\n", microtime(true) - $deb);

$deb = microtime(true);
echo 'PASSWORD_ARGON2I: ';
for($i=0; $i<10; $i++) {
  $h = password_hash("secret$i", PASSWORD_ARGON2I);
}
printf("%.3f\"\n", microtime(true) - $deb);

$deb = microtime(true);
echo 'PASSWORD_ARGON2ID: ';
for($i=0; $i<10; $i++) {
  $h = password_hash("secret$i", PASSWORD_ARGON2ID);
}
printf("%.3f\"\n", microtime(true) - $deb);

$deb = microtime(true);
echo 'PASSWORD_SHA512: ';
for($i=0; $i<10; $i++) {
  $h = password_hash("secret$i", PASSWORD_SHA512);
}
printf("%.3f\"\n", microtime(true) - $deb);

$deb = microtime(true);
echo 'PASSWORD_YESCRYPT: ';
for($i=0; $i<10; $i++) {
  $h = password_hash("secret$i", PASSWORD_YESCRYPT);
}
printf("%.3f\"\n", microtime(true) - $deb);

