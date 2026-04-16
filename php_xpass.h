/*
  +----------------------------------------------------------------------+
  | xpass extension for PHP                                              |
  +----------------------------------------------------------------------+
  | SPDX-FileCopyrightText: Copyright (c) Remi Collet <remi@php.net>     |
  +----------------------------------------------------------------------+
  | This source file is subject to the Modified BSD License that is      |
  | bundled with this package in the file LICENSE, and is available      |
  | through the WWW at <https://opensource.org/license/BSD-3-Clause>.    |
  |                                                                      |
  | SPDX-License-Identifier: BSD-3-Clause                                |
  +----------------------------------------------------------------------+
*/

#ifndef PHP_XPASS_H
# define PHP_XPASS_H

extern zend_module_entry xpass_module_entry;
#define phpext_xpass_ptr &xpass_module_entry

#define PHP_XPASS_VERSION "1.2.1-dev"
#define PHP_XPASS_AUTHOR  "Remi Collet"
#define PHP_XPASS_LICENSE "PHP-3.01"

# if defined(ZTS) && defined(COMPILE_DL_XPASS)
ZEND_TSRMLS_CACHE_EXTERN()
# endif

#endif	/* PHP_XPASS_H */
