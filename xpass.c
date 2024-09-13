/*
  +----------------------------------------------------------------------+
  | xpass extension for PHP                                              |
  +----------------------------------------------------------------------+
  | Copyright (c) The PHP Group                                          |
  +----------------------------------------------------------------------+
  | This source file is subject to version 3.01 of the PHP license,      |
  | that is bundled with this package in the file LICENSE, and is        |
  | available through the world-wide-web at the following url:           |
  | http://www.php.net/license/3_01.txt.                                 |
  | If you did not receive a copy of the PHP license and are unable to   |
  | obtain it through the world-wide-web, please send a note to          |
  | license@php.net so we can mail you a copy immediately.               |
  +----------------------------------------------------------------------+
  | Author: Remi Collet <remi@php.net>                                   |
  +----------------------------------------------------------------------+
*/

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "ext/standard/info.h"
#include "ext/standard/php_password.h"
#include "php_xpass.h"
#include <crypt.h>

#include "xpass_arginfo.h"

/* {{{ PHP_RINIT_FUNCTION */
PHP_RINIT_FUNCTION(xpass)
{
#if defined(ZTS) && defined(COMPILE_DL_XPASS)
	ZEND_TSRMLS_CACHE_UPDATE();
#endif

	return SUCCESS;
}
/* }}} */

/* {{{ PHP_MINFO_FUNCTION */
PHP_MINFO_FUNCTION(xpass)
{
	php_info_print_table_start();
	php_info_print_table_header(2, "Extended password support", "enabled");
	php_info_print_table_row(2, "Extension version", PHP_XPASS_VERSION);
	php_info_print_table_row(2, "libxcrypt version", XCRYPT_VERSION_STR);
	php_info_print_table_row(2, "Author", PHP_XPASS_AUTHOR);
	php_info_print_table_row(2, "License", PHP_XPASS_LICENSE);
#ifdef HAVE_CRYPT_SHA512
	php_info_print_table_row(2, "sha512 hash", "yes");
#else
	php_info_print_table_row(2, "sha512 hash", "no");
#endif
#ifdef HAVE_CRYPT_YESCRYPT
	php_info_print_table_row(2, "yescrypt hash", "yes");
#else
	php_info_print_table_row(2, "yescrypt hash", "no");
#endif
	php_info_print_table_end();
}
/* }}} */

static bool get_options(zend_array *options, zend_ulong *cost) {
	zval *opt;

	*cost = 0;
	if (!options) {
		return true;
	}
	if ((opt = zend_hash_str_find(options, "cost", strlen("cost")))) {
		*cost = zval_get_long(opt);
	}
	return true;
}


static zend_string *php_xpass_hash(const zend_string *password, zend_array *options, const char *algo) {
	struct crypt_data data;
	zend_ulong cost;

	memset(&data, 0, sizeof(data));

	if (!get_options(options, &cost)) {
		return NULL;
	}
	if ((ZSTR_LEN(password) >= CRYPT_MAX_PASSPHRASE_SIZE)) {
		zend_value_error("Password is too long");
		return NULL;
	}
	if (!crypt_gensalt_rn(algo, cost, NULL, 0, data.setting, sizeof(data.setting))) {
		zend_value_error("Bad password options");
		return NULL;
	}
	if (!crypt_r(ZSTR_VAL(password), data.setting, &data)) {
		zend_value_error("Unexpected failure hashing password");
		return NULL;
	}
	return zend_string_init(data.output, strlen(data.output), 0);
}

static zend_string *php_xpass_yescrypt_hash(const zend_string *password, zend_array *options) {
	return php_xpass_hash(password, options, "$y$");
}

static zend_string *php_xpass_sha512_hash(const zend_string *password, zend_array *options) {
	return php_xpass_hash(password, options, "$6$");
}

static bool php_xpass_verify(const zend_string *password, const zend_string *hash) {
	struct crypt_data data;

	memset(&data, 0, sizeof(data));

	if ((ZSTR_LEN(password) >= CRYPT_MAX_PASSPHRASE_SIZE) || (ZSTR_LEN(hash) >= CRYPT_OUTPUT_SIZE)) {
		return false;
	}
	if (!crypt_r(ZSTR_VAL(password), ZSTR_VAL(hash), &data)) {
		return false;
	}
	if (strcmp(data.output, ZSTR_VAL(hash))) {
		return false;
	}
	return true;
}

static bool php_xpass_needs_rehash(const zend_string *hash, zend_array *options) {

	if (crypt_checksalt(ZSTR_VAL(hash)) != CRYPT_SALT_OK) {
		return 1;
	}
	return 0;
}

static const php_password_algo xpass_algo_sha512 = {
	"sha512",
	php_xpass_sha512_hash,
	php_xpass_verify,
	php_xpass_needs_rehash,
	NULL, // php_xpass_yescrypt_get_info,
	NULL,
};

static const php_password_algo xpass_algo_yescrypt = {
	"yescrypt",
	php_xpass_yescrypt_hash,
	php_xpass_verify,
	php_xpass_needs_rehash,
	NULL, // php_xpass_yescrypt_get_info,
	NULL,
};

/* {{{ Generates a salt for algo */
PHP_FUNCTION(crypt_gensalt)
{
	char salt[CRYPT_GENSALT_OUTPUT_SIZE + 1];
	char *prefix = NULL;
	size_t prefix_len = 0;
	zend_long count = 0;

	ZEND_PARSE_PARAMETERS_START(0, 2)
		Z_PARAM_OPTIONAL
		Z_PARAM_STRING(prefix, prefix_len)
		Z_PARAM_LONG(count)
	ZEND_PARSE_PARAMETERS_END();

	if (crypt_gensalt_rn(prefix, (unsigned long)count, NULL, 0, salt, CRYPT_GENSALT_OUTPUT_SIZE)) {
		RETURN_STRING(salt);
	}
	RETURN_NULL();
}
/* }}} */

/* {{{ Get preferred hasing method prefix */
PHP_FUNCTION(crypt_preferred_method)
{
	const char *prefix;

	ZEND_PARSE_PARAMETERS_NONE();

	prefix = crypt_preferred_method();
	if (prefix) {
		RETURN_STRING(prefix);
	}
	RETURN_NULL();
}
/* }}} */

/* {{{ Determine whether the user's passphrase should be re-hashed using the currently preferred hashing method */
PHP_FUNCTION(crypt_checksalt)
{
	char *salt;
	size_t salt_len;

	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_STRING(salt, salt_len)
	ZEND_PARSE_PARAMETERS_END();

	RETURN_LONG(crypt_checksalt(salt));
}
/* }}} */

PHP_MINIT_FUNCTION(xpass) /* {{{ */ {

	register_xpass_symbols(module_number);

#ifdef HAVE_CRYPT_SHA512
	if (FAILURE == php_password_algo_register("6", &xpass_algo_sha512)) {
		return FAILURE;
	}
	REGISTER_STRING_CONSTANT("PASSWORD_SHA512", "6", CONST_CS | CONST_PERSISTENT);
#endif

#ifdef HAVE_CRYPT_YESCRYPT
	if (FAILURE == php_password_algo_register("y", &xpass_algo_yescrypt)) {
		return FAILURE;
	}
	REGISTER_STRING_CONSTANT("PASSWORD_YESCRYPT", "y", CONST_CS | CONST_PERSISTENT);
#endif

	return SUCCESS;
}

/* {{{ xpass_module_entry */
zend_module_entry xpass_module_entry = {
	STANDARD_MODULE_HEADER,
	"xpass",					/* Extension name */
	ext_functions,					/* zend_function_entry */
	PHP_MINIT(xpass),			/* PHP_MINIT - Module initialization */
	NULL,						/* PHP_MSHUTDOWN - Module shutdown */
	PHP_RINIT(xpass),			/* PHP_RINIT - Request initialization */
	NULL,						/* PHP_RSHUTDOWN - Request shutdown */
	PHP_MINFO(xpass),			/* PHP_MINFO - Module info */
	PHP_XPASS_VERSION,			/* Version */
	STANDARD_MODULE_PROPERTIES
};
/* }}} */

#ifdef COMPILE_DL_XPASS
# ifdef ZTS
ZEND_TSRMLS_CACHE_DEFINE()
# endif
ZEND_GET_MODULE(xpass)
#endif
