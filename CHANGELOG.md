# Lathe changelog

## 0.4

Adds basic support for the WPML plugin:

-   A `langauge-switcher` Twig component;
-   The `languages` and `language` values on the Timber context.

Adds `front.twig` in the template hierarchy for `is_front_page()`. The theme ships with a sample `front.twig` template.

Adds `title` in the Timber context for CPT archive pages.

Adds the cache clear actions to the Admin Bar as well as the Site Options page (under the newly-created `AdminHelper.php` file).

Adds the `Social Menu` items to the site footer.

A new `CustomTypesHelper.php` file contains sample snippets for registering custom post types and taxonomies.

Removes support for `post-formats` in the theme.

Adds more useful things to the default CSS.

Registers ACF option pages for Custom Post Type archive pages.

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
