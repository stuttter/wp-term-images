# Contributing

Thanks for helping maintain WP Term Images.

## Before changing behavior

Describe the observable behavior, affected taxonomies, compatibility
expectations, and acceptance criteria in a GitHub issue. Security reports
belong in the private reporting channel described in `SECURITY.md`.

## Pull requests

- Keep each pull request focused and reversible.
- Add regression coverage for behavior changes and bug fixes.
- Preserve the declared PHP and WordPress minimum versions.
- Exercise add, edit, quick-edit, and programmatic term updates when changing
  metadata writes.
- Identify taxonomy-query, media, capability, database, and release impact.
- Do not commit credentials, dependency directories, caches, databases, or
  generated release ZIP files.
- Run `composer phpcs` and `composer test` before requesting review.
- Wait for every required check and resolve review conversations before merge.

AI-assisted contributions are welcome, but the contributor remains responsible
for understanding and validating the result.

## Development requirements

The plugin and its Composer development toolchain require PHP 7.4 or newer.
Install the locked dependencies with `composer install`, then run the test suite
with `composer test`. Run the WordPress and PHP compatibility standards with
`composer phpcs`.
