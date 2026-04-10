dnl config.m4 for extension xpass

PHP_ARG_ENABLE([xpass],
  [whether to enable xpass support],
  [AS_HELP_STRING([--enable-xpass],
    [Enable xpass support])],
  [no])

if test "$PHP_XPASS" != "no"; then
  PKG_CHECK_MODULES([LIBXCRYPT], [libxcrypt >= 4.4])
  PHP_EVAL_INCLINE([$LIBXCRYPT_CFLAGS])
  PHP_EVAL_LIBLINE([$LIBXCRYPT_LIBS], [XPASS_SHARED_LIBADD])

  old_CFLAGS=$CFLAGS; CFLAGS="$CFLAGS $LIBXCRYPT_CFLAGS"
  old_LIBS=$LIBS; LIBS="$LIBS $LIBXCRYPT_LIBS"

  AC_MSG_CHECKING([for yescrypt algo])
  AC_RUN_IFELSE([AC_LANG_SOURCE([[
#include <string.h>
#include <unistd.h>
#include <crypt.h>
#include <stdlib.h>

int main(void) {
    char algo[8], *salt, *hash;
	algo[0]='$'; algo[1]='y'; algo[2]='$'; algo[3]=0;
	salt = crypt_gensalt(algo, 0, NULL, 0);
	if (salt) {
		hash = crypt("secret", salt);
		return (hash && strlen(hash) == 73 && !memcmp(hash, algo, 3) ? 0 : 1);
	}
	return 1;
}]])],[
    AC_DEFINE([HAVE_CRYPT_YESCRYPT], [1], [ Have yescrypt hash support ])
    AC_MSG_RESULT([available])
  ], [
    AC_MSG_RESULT([missing])
  ])

  AC_MSG_CHECKING([for sha512 algo])
  AC_RUN_IFELSE([AC_LANG_SOURCE([[
#include <string.h>
#include <unistd.h>
#include <crypt.h>
#include <stdlib.h>

int main(void) {
    char algo[8], *salt, *hash;
	algo[0]='$'; algo[1]='6'; algo[2]='$'; algo[3]=0;
	salt = crypt_gensalt(algo, 0, NULL, 0);
	if (salt) {
		hash = crypt("secret", salt);
		return (hash && strlen(hash) == 106 && !memcmp(hash, algo, 3) ? 0 : 1);
	}
	return 1;
}]])],[
    AC_DEFINE([HAVE_CRYPT_SHA512], [1], [ Have sha512 hash support ])
    AC_MSG_RESULT([available])
  ], [
    AC_MSG_RESULT([missing])
  ])

  AC_MSG_CHECKING([for sm3 algo])
  AC_RUN_IFELSE([AC_LANG_SOURCE([[
#include <string.h>
#include <unistd.h>
#include <crypt.h>
#include <stdlib.h>

int main(void) {
    char algo[8], *salt, *hash;
	algo[0]='$'; algo[1]='s'; algo[2]='m'; algo[3]='3'; algo[4]='$'; algo[5]=0;
	salt = crypt_gensalt(algo, 0, NULL, 0);
	if (salt) {
		hash = crypt("secret", salt);
		return (hash && strlen(hash) == 65 && !memcmp(hash, algo, 5) ? 0 : 1);
	}
	return 1;
}]])],[
    AC_DEFINE([HAVE_CRYPT_SM3], [1], [ Have sm3 hash support ])
    AC_MSG_RESULT([available])
  ], [
    AC_MSG_RESULT([missing])
  ])

  CFLAGS=$old_CFLAGS
  LIBS=$old_LIBS

  PHP_SUBST([XPASS_SHARED_LIBADD])

  AC_DEFINE([HAVE_XPASS], [1], [ Have xpass support ])

  PHP_NEW_EXTENSION(xpass, xpass.c, $ext_shared)
fi
