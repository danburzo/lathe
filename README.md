# lathe

A Timber-based WordPress starter theme.

## Features

**Twig for squeaky-clean templates.**

**Custom (and customizable) template hierarchy.**

## Required plugins

-   [Timber](https://wordpress.org/plugins/timber-library/)
-   [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/) (the free version) or [ACF Pro](https://www.advancedcustomfields.com/pro/)

## Static resource bundling

This theme is set up to process CSS, JavaScript, and other static resources with [Parcel](https://parceljs.org/).

### Assets Manifest

## Twig functions

### `style()`

A helper function to reference stylesheets in your theme.

```twig
{{ style($handle, $enqueue = true) }}
```

**Options:**

-   `$handle`: the identifier for the stylesheet, as referenced in the [Assets Manifest](#assets-manifest);
-   `$enqueue`: when `true`, it enqueues the stylesheet through WordPress; when `false`, it returns an absolute URL to the stylesheet.

### `script()`

Works the same way as `style()`, but for scripts.

```twig
{{ script($handle, $enqueue = true) }}
```

**Options:**

-   `$handle`: the identifier for the script, as referenced in the [Assets Manifest](#assets-manifest);
-   `$enqueue`: when `true`, it enqueues the script through WordPress; when `false`, it returns an absolute URL to the script.

## Twig filters
