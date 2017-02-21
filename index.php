<?php
/**
 * The template used for the 'Blog' page - ie, when we've set the front page
 * to be a static page and chosen another page for the Posts Page, this is
 * what's displayed.
 *
 * We want it to look just like an archive page, so that it looks the same
 * as the template used for the front Reading page.
 *
 * So we just include the parent theme's archive.php template!
 */

include( get_template_directory() . '/archive.php' );

?>
