# Lathe changelog

## 0.3

Introduces a `pre_get_posts` hook for adjusting the main WP Query object. As an example adjustment, post type archives will only show top-level posts of that type, instead of including descendant posts as well.

Cleans up the context for archive / search pages.

Adds a fix for [issue #18282](https://core.trac.wordpress.org/ticket/18282) about nested pages in the Menu editor.

Lazy-loads the assets manifest on the first `asset()` usage.

Sets the Twig cache in Timber based on `WP_DEBUG`, and creates a meta box on the Site Options page to clear the cache.

## 0.2

Replaced the `style()` and `script()` functions to a single `asset()` function, and added the `inline` option. Also added an `asset` equivalent filter.

## 0.1

Initial version.
