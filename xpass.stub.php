<?php

/**
 * @generate-class-entries
 * @generate-legacy-arginfo 80000
 */


/** @var string */
const CRYPT_PREFIX_STD_DES = '';
/** @var string */
const CRYPT_PREFIX_EXT_DES = '_';
/** @var string */
const CRYPT_PREFIX_MD5 = '$1$';
/** @var string */
const CRYPT_PREFIX_BLOWFISH = '$2y$';
/** @var string */
const CRYPT_PREFIX_SHA256 = '$5$';
/** @var string */
const CRYPT_PREFIX_SHA512 = '$6$';
/** @var string */
const CRYPT_PREFIX_SCRYPT = '$7$';
/** @var string */
const CRYPT_PREFIX_GOST_YESCRYPT = '$gy$';
/** @var string */
const CRYPT_PREFIX_YESCRYPT = '$y$';

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


function crypt_gensalt(?string $prefix = null, int $count = 0): ?string {}

function crypt_preferred_method(): ?string {}

function crypt_checksalt(string $salt): int {}

