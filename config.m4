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
  old_LIBS="$LIBS"; LIBS="$LIBXCRYPT_LIBS"

  AC_MSG_CHECKING([for yescrypt])
  AC_LINK_IFELSE([AC_LANG_PROGRAM([[
      #include <crypt.h>
      #include <string.h>
  ]], [[
      struct crypt_data data;
      memset(&data, 0, sizeof(data));
      char *result = crypt_r("password", "$y$", &data);
      return (result != NULL && strncmp(result, "$y$", 3) == 0) ? 0 : 1;
  ]])], [
      AC_MSG_RESULT([available])
      AC_DEFINE([HAVE_CRYPT_YESCRYPT], [1], [ Have yescrypt hash support ])
  ], [
      AC_MSG_RESULT([missing])
  ])

  AC_MSG_CHECKING([for sha512 algo])
  AC_LINK_IFELSE([AC_LANG_PROGRAM([[
      #include <crypt.h>
      #include <string.h>
  ]], [[
      struct crypt_data data;
      memset(&data, 0, sizeof(data));
      char *result = crypt_r("password", "$6$salt", &data);
      return (result != NULL && strncmp(result, "$6$", 3) == 0) ? 0 : 1;
  ]])],[
    AC_DEFINE([HAVE_CRYPT_SHA512], [1], [ Have sha512 hash support ])
    AC_MSG_RESULT([available])
  ], [
    AC_MSG_RESULT([missing])
  ])

  CFLAGS=$old_CFLAGS
  LDFLAGS=$old_LDFLAGS

  PHP_SUBST([XPASS_SHARED_LIBADD])

  AC_DEFINE([HAVE_XPASS], [1], [ Have xpass support ])

  PHP_NEW_EXTENSION(xpass, xpass.c, $ext_shared)
fi
