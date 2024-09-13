# xpass extension for PHP

This extension provides password hashing algorithms used by Linux distributions.

* **sha512** (`$6$`) provided for legacy as used on some old distributions (ex: RHEL-8)
* **yescrypt** (`$y$`) used on modern distributions

Notices: these can be fast, don't expect improved security level.

It also provides additional functions from libxcrypt missing in core PHP:

* `crypt_preferred_method`: get the prefix of the preferred hash method
* `crypt_gensalt`: encode settings for passphrase hashing
* `crypt_checksalt`: validate a crypt setting string

See the Linux man pages.

**Computation time**

* bcrypt: 0.33"
* argon2i: 0.53"
* argon2id: 0.55"
* sha512: 0.01"
* yescrypt: 0.07" with default cost of 5
* yescrypt: 0.14" with cost=6
* yescrypt: 0.30" with cost=7
* yescrypt: 0.62" with cost=8

# Sources

* Official git repository: https://git.remirepo.net/cgit/tools/php-xpass.git/
* Mirror on github for contributors: https://github.com/remicollet/php-xpass

# Build

Compatible with PHP 8.0 or greater.

You need the Extended crypt library development files (libxcrypt-devel, libcrypt-dev)
version 4.4 or greater.

From the sources tree

    $ phpize
    $ ./configure --enable-xpass
    $ make
    $ make test

# Usage

## password hashing and verifying

    $ php -a

    php > var_dump($hash = password_hash("secret", PASSWORD_SHA512));
    string(106) "$6$y2T3Ql8zEgzbpZeK$s42Q92ggqycC280QMx4.bid1gKI8ghM7ZQJF.F.fbY49Cqj/gnS9h3CiOXyYh0pvtisqiNavSPJP8ZR9Ty7RX1"
    
    php > var_dump($hash = password_hash("secret", PASSWORD_YESCRYPT));
    string(73) "$y$j9T$X9Va6i3zHjyKGJAskYZPv.$i1m/WR1C6/tqhB7IdOsi9Ar1JF4Qr38vBx104ao1OS5"

    php > var_dump($hash = password_hash("secret", PASSWORD_YESCRYPT, ['cost'=>7]));
    string(73) "$y$jBT$bo5CcI5fdsyad1Av.vgLQ.$FgOq74zufVvkOL/q4OBmcKDMMXJB9VzrJXEZrhoVjf6"

    php > var_dump(password_verify("secret", $hash));
    bool(true)

## supported options

* `cost` controls the CPU time cost of the hash. If `cost` is 0, a reasonable default cost will be selected.

## crypt helpers

    $ php -a

    php > var_dump(crypt_preferred_method());
    string(3) "$y$"

    php > var_dump($salt = crypt_gensalt());
    string(29) "$y$j9T$EitfN8MxRjFzV5tNe97D70"

    php > var_dump(crypt_checksalt($salt) == CRYPT_SALT_OK);
    bool(true)

    php > var_dump($hash = crypt("secret", $salt));
    string(73) "$y$j9T$EitfN8MxRjFzV5tNe97D70$vGtxczdGMTLh0LfpwxAmyzgba7EODsmazEh03kpvbh3"

    php > var_dump($hash === crypt("secret", $hash));
    bool(true)

# LICENSE

Author: Remi Collet

This extension is licensed under [The PHP License, version 3.01](http://www.php.net/license/3_01.txt)

# History

Created on user request for consistency with system crypto.
