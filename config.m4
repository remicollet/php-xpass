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
  old_LDFLAGS=$LDFLAGS; LDFLAGS="$LDFLAGS"
  old_LIBS=$LIBS; LIBS="$LIBS $LIBXCRYPT_LIBS"

  AC_MSG_CHECKING([for yescrypt])
  AC_RUN_IFELSE([AC_LANG_SOURCE([[
#include <string.h>
#include <unistd.h>
#include <crypt.h>
#include <stdlib.h>

int main(void) {
    char salt[8];
	salt[0]='$'; salt[1]='y'; salt[2]='$'; salt[3]=0;
	return crypt_gensalt(salt, 0, NULL, 0) ? 0 : 1;
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
    char salt[8];
	salt[0]='$'; salt[1]='6'; salt[2]='$'; salt[3]=0;
	return crypt_gensalt(salt, 0, NULL, 0) ? 0 : 1;
}]])],[
    AC_DEFINE([HAVE_CRYPT_SHA512], [1], [ Have sha512 hash support ])
    AC_MSG_RESULT([available])
  ], [
    AC_MSG_RESULT([missing])
  ])

  CFLAGS=$old_CFLAGS
  LDFLAGS=$old_LDFLAGS
  LIBS=$old_LIBS

  PHP_SUBST([XPASS_SHARED_LIBADD])

  AC_DEFINE([HAVE_XPASS], [1], [ Have xpass support ])

  PHP_NEW_EXTENSION(xpass, xpass.c, $ext_shared)
fi
