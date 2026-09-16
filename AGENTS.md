# WP Term Images contributor guidance

## Compatibility

- Preserve PHP 7.4 and WordPress 6.4 compatibility unless a dedicated pull
  request explicitly changes the published minimums.
- Treat term metadata writes, taxonomy queries, media-library integration, and
  quick editing as elevated-risk behavior.
- Preserve the `image` term-meta key, public classes, hooks, filter arguments,
  asset handles, and developer-facing behavior unless a deprecation path is
  part of the change.

## Tests

- Add a regression test before changing observed behavior.
- Characterize explicit removal, programmatic term updates, taxonomy targeting,
  sorting, and stored attachment IDs when changing those paths.
- Run `composer test`, the declared PHP syntax matrix, and metadata/artifact
  validation before requesting review.

## Releases

The source, readme stable tag, Git tag, and WordPress.org version must agree
before publishing. WordPress.org currently requires special attention because
its historical release state drifted behind GitHub.

## Automation

Follow the organization-level safety boundaries. AI-authored implementation
must remain a draft pull request and cannot modify workflows, release policy,
ownership, security policy, or this file without explicit maintainer
authorization.
