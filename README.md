# WP-Pygments

Plug the Python syntax highlighter [Pygments](https://pygments.org/) into
WordPress to turn every `<pre><code>` into highlighted code server-side, 
no JavaScript required!

Originally created by [@wellingguzman](https://github.com/wellingguzman), this repo was a fork.  
MIT license and continuation of the plugin with [permission](https://twitter.com/wellingguzman/status/1381198380584558594).

# THIS IS JUST A SIMPLE PLUGIN, NOT HIGHLY TESTED, USE IT AT YOUR OWN RISK

## Tutorial

You can read the tutorial here <http://davidwalsh.name/pygments-php-wordpress>, not on how to use it, but how [@wellingguzman](https://github.com/wellingguzman) wrote it.

## How to use it

1. Copy `WP-Pygments` into your plugins directory in WordPress
2. Head to `wp-admin/plugins.php` and activate `WP Pygments`
3. Now every `<pre><code>` you have in your post/page content would turn into pygments highlighted code

Ex:

```html
<pre class="php">
<code>
  <?php
    function hello( $name ) {
      return 'Hello, ' . $name;
    }

    echo hello( 'Mundo' );
  ?>
</code>
</pre>
```
