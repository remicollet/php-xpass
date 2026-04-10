# Unreleased

- improve algo availability check

# Version 1.2.0 - 2026-01-13

- add SM3 hash algos available in libxcrypt 4.5
- add CRYPT_PREFIX_SM3CRYPT and CRYPT_PREFIX_SM3_YESCRYPT constants
- add PASSWORD_SM3CRYPT and PASSWORD_SM3_YESCRYPT constants

# Version 1.1.0 - 2024-09-26

- add `crypt_gensalt(?string $prefix = null, int $count = 0): ?string {}`
- add `crypt_preferred_method(): ?string {}`
- add `crypt_checksalt(string $salt): int {}`
- add `CRYPT_PREFIX_*` and `CRYPT_SALT_*` constants

# Version 1.0.0 - 2024-09-09

- first GA release

# Version 1.0.0RC2 - 2024-09-02

- fix libxcrypt algorithm detection (@zeriyoshi)

# Version 1.0.0RC2 - 2024-08-28

- first RC release
