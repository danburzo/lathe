# Lathe

A Timber-based WordPress starter theme.

Since it's written around it, Lathe requires the [Timber](https://wordpress.org/plugins/timber-library/) plugin to work. It also adds some neat functionality when you have [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/) (the free version) or [ACF Pro](https://www.advancedcustomfields.com/pro/) installed.

Here's what you get out of the box:

-   **A neat template structure.** In Twig, templates can extend and override parts of other templates. This allows us to organize our HTML code in chunks that make sense.
-   **A custom (and customizable) template hierarchy.** WordPress's template hierarchy is one of its cooler features, but customizing it is kind of a hassle. That's why, in this house, we manage the template hierarchy ourselves.
-   **A pipeline for processing static assets.** This unlocks a lot of flexibility to work with whichever flavor of CSS or JavaScript you want.
-   **Useful pre-made things.** Lathe includes some Twig functions, filters, and components which you can hack to your liking.

## Guides

### Static resource bundling

This theme is set up to process CSS, JavaScript, and other static resources with [Parcel](https://parceljs.org/).

#### Setting up

You'll need to have Node and Yarn installed to use static resource bundling. Run `yarn` in your theme folder to install all the dependencies.

#### npm scripts

There are a couple of scripts available:

-   `yarn start` — builds the resources as you work on them, reacting to changes
-   `yarn build` — builds the resources for production

> The bundles are generated in the `static/dist` folder. Make sure you don't change these files by hand, as your changes will be overwritten!

#### Assets Manifest

The set of files to process is defined in the `assets-manifest.html` file. Here's an example:

**asset-manifest.html**

```html
<script src="static/index.js"></script>
<link href="style.css" rel="stylesheet" />
```

You add CSS files you want to process as `<link>` elements, and JavaScript as `<script>` elements. Then, in your theme code, you can use the `style()` and `script()` Twig functions to include these assets on the pages that need them.

For the two assets included in our example manifest, the equivalent Twig code to include them is:

**templates/my-template.twig**

```twig
{{ script('static/index.js') }}
{{ style('style.css') }}
```

We don't refer to the assets by the bundle path. If you take a look in `static/dist`after a build, you may notice the paths contain _hashes_ — sequences of alphanumeric characters that help deal with the browser cache. A file's hash updates whenever you make a change to the file, so if we were to refer to it as such, we'd constantly have to tweak our theme code.

Instead, we refer to their path _relative to the root folder_ of your theme. The `$handle` parameter always matches the `src` / `href` attributes we use in our Asset Manifest.

> Sidenote: Why this weird system? To my mind, it's currently [the easiest way](https://github.com/parcel-bundler/parcel/issues/2611) to pair Parcel with a WordPress theme.

## Reference

### Twig functions

#### The `style()` function

A helper function to reference stylesheets in your theme.

```twig
{{ style($handle, $enqueue = true) }}
```

**Options:**

-   `$handle`: the identifier for the stylesheet, as referenced in the [Assets Manifest](#assets-manifest);
-   `$enqueue`: when `true`, it enqueues the stylesheet through WordPress; when `false`, it returns an absolute URL to the stylesheet.

#### The `script()` function

Works the same way as `style()`, but for scripts.

```twig
{{ script($handle, $enqueue = true) }}
```

**Options:**

-   `$handle`: the identifier for the script, as referenced in the [Assets Manifest](#assets-manifest);
-   `$enqueue`: when `true`, it enqueues the script through WordPress; when `false`, it returns an absolute URL to the script.

### Twig filters

TBD.
