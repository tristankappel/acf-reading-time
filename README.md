# ACF Reading Time

A lightweight WordPress plugin that outputs the estimated reading time of a post via a shortcode. It counts the post content **and every Advanced Custom Fields (ACF Pro) value** attached to the post, automatically, with no field selection needed in the backend.

- **Author:** [Tristan Kappel](https://tristankappel.de)
- **License:** GPL-2.0-or-later
- **Requires:** WordPress 5.8+, PHP 7.4+

## Features

- `[reading_time]` shortcode for any post or template.
- Counts the post content plus all ACF fields (repeaters, groups, flexible content and nested sub fields are traversed recursively).
- Works with or without ACF; ACF Pro and all its field types are supported.
- Minimal settings page: **prefix**, **postfix**, and **words per minute**.
- Translation ready (text domain `acf-reading-time`).

## Installation

1. Copy the `acf-reading-time` folder into `wp-content/plugins/`.
2. Activate **ACF Reading Time** in the WordPress admin.
3. Go to **Settings → ACF Reading Time** to set the prefix, postfix and words per minute.

## Usage

Add the shortcode inside a post or page:

```
[reading_time]
```

In a template file:

```php
echo do_shortcode( '[reading_time]' );
```

### Shortcode attributes

| Attribute | Description | Default |
|-----------|-------------|---------|
| `post_id` | Post to measure | current post |
| `prefix`  | Text before the time | from settings |
| `postfix` | Text after the time | from settings |
| `wpm`     | Words per minute | from settings |

Example:

```
[reading_time wpm="250" prefix="Estimated:" postfix="minutes"]
```

## Settings

Under **Settings → ACF Reading Time**:

- **Prefix (before time)** – e.g. `Reading time:`
- **Postfix (after time)** – e.g. `min read`
- **Words per minute** – average reading speed (default `200`)

## Developer filter

Filter the text used for counting before it is processed:

```php
add_filter( 'acf_reading_time_text', function ( $text, $post_id ) {
    // Modify $text here.
    return $text;
}, 10, 2 );
```

## License

This project is licensed under the GNU General Public License v2.0 or later. See [`LICENSE`](LICENSE).
