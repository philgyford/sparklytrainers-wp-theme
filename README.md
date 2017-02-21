# Sparklytrainers WordPress theme

This is a child theme of the Twenty Sixteen theme.

It adds:

* A custom post type called 'Reviews'.
* A category-style taxonomy called 'Genres', used only with Reviews.
* A tag-style taxonomy called 'Authors', used only with Reviews.
* Date-based URLs for Reviews and slug-based reviews for the taxonomies:

	/reading/2017/02/19/post-name/  # A single Review.
	/reading/2017/02/19/            # Reviews from one day.
	/reading/2017/02/               # Reviews from one month.
	/reading/2017/                  # Reviews from one year
	/reading/                       # The most recent Reviews.
	/reading/genre/genre-name/      # Reviews in a Genre.
	/reading/author/author-name/    # Reviews by an Author.

* Standard Posts (with Categories and Tags) can still be used as a separate
	blog.
* The front page of the site will display the most recent Posts and Reviews
	combined.


## Installation

Check it out to a `twentysixteen-child` directory in `wp-content/themes/`.

Activate the theme in Appearance > Themes.

The following are things I've done; not sure which are essential to use the
theme:

* Create two Pages: 'Home' and 'Blog'.
* In Settings > Reading, for 'Front page displays', choose 'A static page'. The
	front page should be 'Home' and the Posts page should be 'Blog'.
* In Settings > Permalinks I set a custom structure of `/blog/%year%/%monthnum%/%day%/%postname%/`. This is used for standard Posts, and is consistent with the Review structure declared in `functions.php`.
* The [Custom Post Type Widgets plugin](https://wordpress.org/plugins/custom-post-type-widgets/) is useful for displaying standard-looking widgets but for Reviews.

