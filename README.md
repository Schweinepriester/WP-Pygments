WP-Pygments
===========

Use Pygment syntax highlighter on Wordpress (PHP)


# THIS IS JUST A SIMPLE PLUGIN, NOT HIGHLY TESTED, USE IT AT YOUR OWN RISK

Tutorial
---------

You can read the tutorial here http://davidwalsh.name/pygments-php-wordpress, not on how to use it, but how I wrote it.


How to use it
-------------

Copy `WP-Pygments` into your plugins directory in WorPress.

Head to wp-admin/plugins.php and Activate `WP Pygments`.

Now every `<pre><code>` you have in your post/page content would turn into pygments highlighted code.

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