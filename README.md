# Lathe

A Timber-based WordPress starter theme.

Since it's written around it, Lathe requires the [Timber](https://wordpress.org/plugins/timber-library/) plugin to work. It also adds some neat functionality when you have [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/) (the free version) or [ACF Pro](https://www.advancedcustomfields.com/pro/) installed.

Here's what you get out of the box:

-   **A neat template structure.** In Twig, templates can extend and override parts of other templates. This lets us organize our markup in chunks that make sense.
-   **A custom (and customizable) template hierarchy.** WordPress's template hierarchy is one of its cooler features, but customizing it is kind of a hassle. That's why, in this house, we manage the template hierarchy ourselves.
-   **A pipeline for processing static assets.** This unlocks a lot of flexibility to work with whichever flavor of CSS or JavaScript you want.
-   **Useful pre-made things.** Lathe includes some Twig functions, filters, and components which you can hack to your liking.

## Table of contents

-   [Guides](#guides)
    -   [Getting started](#getting-started)
    -   [Static assets bundling](#static-assets-bundling)
    -   [Custom post types and taxonomies](#custom-post-types-and-taxonomies)
-   [Reference](#reference)
    -   [Twig functions](#twig-functions)
    -   [Twig filters](#twig-filters)
-   [Related projects](#related-projects)
-   [Contributing](#contributing)

## Guides

### Getting started

Lathe is designed as a starting point for developing your theme. To get started:

-   [Use Lathe as a template](https://github.com/danburzo/lathe/generate) for a new GitHub repository
-   [Download Lathe as a ZIP](https://github.com/danburzo/lathe/archive/master.zip)
-   Fetch Lathe from the command line in your current directly with `npx degit danburzo/lathe`

When you add the theme to your WordPress installation, make sure install the Timber plugin and, optionally, a version of the ACF plugin.

The [Timber docs](https://timber.github.io/docs/) is a handy reference for developing your theme.

### Static assets bundling

This theme is set up to process CSS, JavaScript, and other static assets with [esbuild](https://esbuild.github.io/). You'll need to have Node and npm installed to use static assets bundling. Run `npm install` in your theme folder to install all the dependencies.

#### npm scripts

There are a couple of scripts available:

-   `npm run start` — builds the assets in development mode and watches for changes
-   `npm run build` — builds the assets for production

> The bundles are _automatically_ generated in the `build/` folder. If you change these files by hand, they risk being overwritten!

#### Assets Manifest

The set of files to process is defined in the `assets.txt` file, located in the theme's root folder. Here's an example:

**assets.txt**

```bash
# Front-end scripts
static/index.js

# Stylesheets
style.css
```

The format is simple: you include one file per line, and lines starting with `#` are considered to be comments and thus ingored by the processor.

In your theme code, use the [`asset()`](#the-asset-function) Twig function to include any of these assets on the pages that need them. For the two assets included in our example manifest, the equivalent Twig code to include them is:

**templates/my-template.twig**

```twig
{{ asset('static/index.js', true) }}
{{ asset('style.css', true) }}
```

When the page gets rendered, you'll see:

```html
<link
	rel="stylesheet"
	id="style.css-css"
	type="text/css"
	media="all"
	href="http://example.com/wp-content/themes/lathe/build/style.281d1dd0.css?ver=5.2.2"
/>

<script
	type="text/javascript"
	src="http://example.com/wp-content/themes/lathe/build/index.117076fb.js?ver=5.2.2"
></script>
```

Notice that the paths to the bundled assets contain _hashes_ — sequences of alphanumeric characters that help deal with the browser cache. Whenever you make a change to a file, its gets a new hash.

### Custom post types and taxonomies

Custom Post Types (CPT) and custom taxonomies are ideally handled in dedicated plugins rather than in the theme. Lathe includes sample code for a CPT and a taxonomy in the `wp-plugins` folder.

The `wp-plugins` folder should be excluded when syncing theme files to the server. Instead, its content should be copied separately to the `wp-content/plugins/` folder.

## Reference

### Twig functions

#### The `asset()` function

A helper function to reference bundled assets in your theme. See [Static assets bundling](#static-assets-bundling) for more details.

```twig
{{ asset($handle, $enqueue = false) }}
```

**Options:**

**`$handle`:** The identifier for the asset, which is the path you referenced in your [Assets Manifest](#assets-manifest). This will normally be the path to the asset relative to the root of your theme.

**`$enqueue`** defines how the asset should be included:

-   when `true`, it enqueues the asset through WordPress's `wp_enqueue_script` or `wp_enqueue_style` (only CSS and JavaScript files support this mode).
-   when `false`, it returns an absolute URL to the asset;
-   when `"inline"`, it writes the asset inline.

### Twig filters

#### The `asset` filter

You can call the [`asset()`](#the-asset-function) function as a filter, too. These are equivalent:

```twig
{# As a function... #}
{{ asset('style.css', true) }}

{# ...or as a filter #}
{{ 'style.css' | asset(true) }}
```

#### The `size` filter

Timber already has a lot of flexibility in [dealing with images](https://timber.github.io/docs/guides/cookbook-images/). This theme adds the `size` filter, allowing you resize images from a set of predefined sizes.

Usage:

```twig
{% if post.thumbnail %}
	<img src='{{ Image(post.thumbnail).src | size("thumbnail") }}'/>
{% endif %}
```

The `static $image_sizes` definition in `function.php` lets you configure the predefined sizes.

> _Note_: These are different from the Media sizes you can configure from the WordPress admin UI.

## Related projects

This theme is inspired by Timber's own [starter theme](https://github.com/timber/starter-theme) and borrows some tricks from [Skela](https://github.com/Upstatement/skela-wp-theme) by Upstatement.

## Contributing

Contributions are welcome. Please open a GitHub issue before submitting a pull request, unless the changes are straightforward.
