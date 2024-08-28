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

#ifndef PHP_XPASS_H
# define PHP_XPASS_H

extern zend_module_entry xpass_module_entry;
#define phpext_xpass_ptr &xpass_module_entry

#define PHP_XPASS_VERSION "1.0.0RC1"
#define PHP_XPASS_AUTHOR  "Remi Collet"
#define PHP_XPASS_LICENSE "PHP-3.01"

# if defined(ZTS) && defined(COMPILE_DL_XPASS)
ZEND_TSRMLS_CACHE_EXTERN()
# endif

#endif	/* PHP_XPASS_H */
