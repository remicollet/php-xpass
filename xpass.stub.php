<?php

/**
 * @generate-class-entries
 * @generate-legacy-arginfo 80000
 */


/* use XPASS prefix to avoid conflicts with standard constants */

/** @var string */
const XPASS_CRYPT_STD_DES = '';
/** @var string */
const XPASS_CRYPT_EXT_DES = '_';
/** @var string */
const XPASS_CRYPT_MD5 = '$1$';
/** @var string */
const XPASS_CRYPT_BLOWFISH = '$2y$';
/** @var string */
const XPASS_CRYPT_SHA256 = '$5$';
/** @var string */
const XPASS_CRYPT_SHA512 = '$6$';
/** @var string */
const XPASS_CRYPT_SCRYPT = '$7$';
/** @var string */
const XPASS_CRYPT_GOST_YESCRYPT = '$gy$';
/** @var string */
const XPASS_CRYPT_YESCRYPT = '$y$';

/**
 * @var int
 * @cvalue CRYPT_SALT_OK
 */
const CRYPT_SALT_OK = UNKNOWN;
/**
 * @var int
 * @cvalue CRYPT_SALT_INVALID
 */
const CRYPT_SALT_INVALID = UNKNOWN;
/**
 * @var int
 * @cvalue CRYPT_SALT_METHOD_DISABLED
 */
const CRYPT_SALT_METHOD_DISABLED = UNKNOWN;
/**
 * @var int
 * @cvalue CRYPT_SALT_METHOD_LEGACY
 */
const CRYPT_SALT_METHOD_LEGACY = UNKNOWN;
/**
 * @var int
 * @cvalue CRYPT_SALT_TOO_CHEAP
 */
const CRYPT_SALT_TOO_CHEAP = UNKNOWN;


function crypt_gensalt(?string $salt = null, int $count = 0): ?string {}

function crypt_preferred_method(): ?string {}

function crypt_checksalt(string $salt): int {}

