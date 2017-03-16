<?php

/**
 * CONTENTS:
 *
 * 1. CREATE CUSTOM POST TYPE AND TAXONOMIES.
 * 2. FURTHER CHANGES NEEDED BY OUR CUSTOM POST TYPE.
 * 3. OVERRIDE SOME PARENT THEME METHODS
 * 4. OTHER MODIFICATIONS.
 */


/******************************************************************************
 * 1. CREATE CUSTOM POST TYPE AND TAXONOMIES.
 */

/**
 * Create the Review custom post type.
 */
function sparkly_create_review_post_type() {

	// Add the custom post type itself.
	register_post_type(
		'sparkly_review',
		array(
			'public' => true,
			'has_archive' => true,
			'hierarchical' => false,
			//'rewrite' => false,
			'rewrite' => array(
				// So the front page of recent Reviews will be at /reading/
				'slug' => 'reading',
				'with_front' => false,
			),
			'capability_type' => 'post',
			'menu_position' => 5, // places menu item directly below Posts
			'menu_icon' => 'dashicons-book-alt',
			'labels' => array(
				'name' => __( 'Reviews' ),
				'singular_name' => __( 'Review' )
			),
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions',),
		)
	);

	// Add the permalink structure we want:
	add_permastruct(
		'sparkly_review',
		'/reading/%year%/%monthnum%/%day%/%name%/',
		false
	);

	// Matching nice URLs to the URLs WP will use to get review(s)...

	// All reviews from one day:
	add_rewrite_rule(
		'^reading/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})(/page/([0-9]+))?/?$',
		'index.php?post_type=sparkly_review&year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&paged=$matches[5]',
		'top'
	);
	// An individual review:
	add_rewrite_rule(
		'^reading/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/?$',
		'index.php?post_type=sparkly_review&name=$matches[4]',
		'top'
	);
	// All reviews from one month:
	add_rewrite_rule(
		'^reading/([0-9]{4})/([0-9]{1,2})(/page/([0-9]+))?/?$',
		'index.php?post_type=sparkly_review&year=$matches[1]&monthnum=$matches[2]&paged=$matches[4]',
		'top'
	);
	// All reviews from one year:
	add_rewrite_rule(
		'^reading/([0-9]{4})(/page/([0-9]+))?/?$',
		'index.php?post_type=sparkly_review&year=$matches[1]&paged=$matches[3]',
		'top'
	);
}
add_action( 'init', 'sparkly_create_review_post_type', 10);


/**
 * Have our custom post type, Reviews, use the nice permalinks we wanted.
 * Should tie in with the add_permastruct() we used when creating the post
 * type.
 */
function sparkly_review_permalinks( $url, $post ) {
    if ( 'sparkly_review' == get_post_type( $post ) ) {
        $url = str_replace( "%year%", get_the_date('Y'), $url );
        $url = str_replace( "%monthnum%", get_the_date('m'), $url );
        $url = str_replace( "%day%", get_the_date('d'), $url );
        $url = str_replace( "%name%", $post->post_name, $url );
    }
    return $url;
}
add_filter( 'post_type_link', 'sparkly_review_permalinks', 10, 2 );


/**
 * Create the Authors taxonomy - like tags - and associate it with the
 * Reviews.
 */
function sparkly_authors_init() {
	$labels = array(
		'name' => _x( 'Authors', 'taxonomy general name' ),
		'singular_name' => _x( 'Author', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Authors' ),
		'popular_items' => __( 'Popular Authors' ),
		'all_items' => __( 'All Authors' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Author' ), 
		'update_item' => __( 'Update Author' ),
		'add_new_item' => __( 'Add New Author' ),
		'new_item_name' => __( 'New Author Name' ),
		'separate_items_with_commas' => __( 'Separate authors with commas' ),
		'add_or_remove_items' => __( 'Add or remove authors' ),
		'choose_from_most_used' => __( 'Choose from the most used authors' ),
		'menu_name' => __( 'Authors' ),
		'menu_icon' => 'dashicons-admin-users',
	);
	register_taxonomy(
		'sparkly_authors', // The taxonomy name.
		'sparkly_review',  // Associate it with our custom post type.
		array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'show_tagcloud' => true,
			'show_admin_column' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array(
				// So Reviews by an Author will be found at
				// /reading/author/author-slug/
				'slug' => 'reading/author',
				'with_front' => false
		   	),
			'capabilities' => array()
		)
	);
}
add_action( 'init', 'sparkly_authors_init' );


/**
 * Create the Genres taxonomy - like categories - and associate it with the
 * Reviews.
 */
function sparkly_genres_init() {

	$labels = array(
		'name' => _x( 'Genres', 'taxonomy general name' ),
		'singular_name' => _x( 'Genre', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Genres' ),
		'all_items' => __( 'All Genres' ),
		'parent_item' => __( 'Parent Genre' ),
		'parent_item_colon' => __( 'Parent Genre:' ),
		'edit_item' => __( 'Edit Genre' ), 
		'update_item' => __( 'Update Genre' ),
		'add_new_item' => __( 'Add New Genre' ),
		'new_item_name' => __( 'New Genre Name' ),
		'menu_name' => __( 'Genres' ),
	); 	

	register_taxonomy(
		'sparkly_genres', // The taxonomy name.
		'sparkly_review', // Associate it with our custom post type.
		array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array(
				// So Reviews in one Genre will be found at
				// /reading/genre/science-fiction/
				'slug' => 'reading/genre',
				'with_front' => false
			),
		)
	);
}
add_action( 'init', 'sparkly_genres_init', 0 );


/******************************************************************************
 * 2. FURTHER CHANGES NEEDED BY OUR CUSTOM POST TYPE.
 */


/**
 * Add some of the body classes that standard Posts have on their single
 * archive pages, so that Reviews' single archive pages behave the same way.
 */
function sparkly_review_pages_classes($classes) {
	$classes[] = 'single';
	$classes[] = 'single-post';
	$classes[] = 'single-format_standard';
	$classes[] = 'post-template-default';
	return $classes;
}
add_filter('body_class','sparkly_review_pages_classes');


/**
 * Add the Review custom post type to the At a Glance widget on Dashboard.
 */ 
function add_custom_post_counts() {
	// array of custom post types to add to 'At A Glance' widget
	$post_types = array('sparkly_review');
	foreach ($post_types as $pt) {
		$pt_info = get_post_type_object($pt); // get a specific CPT's details
		$num_posts = wp_count_posts($pt); // retrieve number of posts associated with this CPT
		$num = number_format_i18n($num_posts->publish); // number of published posts for this CPT
		$text = _n( $pt_info->labels->singular_name, $pt_info->labels->name, intval($num_posts->publish) ); // singular/plural text label for CPT
		echo '<li class="'.$pt_info->name.'-count"><a href="edit.php?post_type='.$pt.'">'.$num.' '.$text.'</a></li>';
	}
}
add_action('dashboard_glance_items', 'add_custom_post_counts');


/**
 * Add the dashicons-book-alt icon to the Reviews item on the Dashboard's
 * At a Glance panel.
 */
function sparkly_admin_head() {
  echo '<style>
	#dashboard_right_now .sparkly_review-count a:before {
		content: "\f331";
	}
  </style>';
}
add_action('admin_head', 'sparkly_admin_head');


/******************************************************************************
 * 3. OVERRIDE SOME PARENT THEME METHODS
 */


/**
 * Override default fonts URL function.
 * Stop loading Google fonts - our theme's CSS isn't using them.
 */
function twentysixteen_fonts_url() {
	return '';
}


/**
 * Overriding the default twentysixteen_entry_meta() from 
 * twentysixteen/inc/template-tags.php so that it works for `sparkly_review`
 * post type.
 */
function twentysixteen_entry_meta() {
	if ( in_array( get_post_type(), array( 'post', 'sparkly_review' ) ) ) {
		$author_avatar_size = apply_filters( 'twentysixteen_author_avatar_size', 49 );
		printf( '<span class="byline"><span class="author vcard">%1$s<span class="screen-reader-text">%2$s </span> <a class="url fn n" href="%3$s">%4$s</a></span></span>',
			get_avatar( get_the_author_meta( 'user_email' ), $author_avatar_size ),
			_x( 'Author', 'Used before post author name.', 'twentysixteen' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			get_the_author()
		);
	}

	if ( in_array( get_post_type(), array( 'post', 'attachment', 'sparkly_review' ) ) ) {
		twentysixteen_entry_date();
	}

	$format = get_post_format();
	if ( current_theme_supports( 'post-formats', $format ) ) {
		printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
			sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', 'twentysixteen' ) ),
			esc_url( get_post_format_link( $format ) ),
			get_post_format_string( $format )
		);
	}

	if ( in_array( get_post_type(), array( 'post', 'sparkly_review' ) ) ) {
		twentysixteen_entry_taxonomies();
	}

	if ( ! is_singular() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'twentysixteen' ), get_the_title() ) );
		echo '</span>';
	}
}


/**
 * Overriding the default twentysixteen_entry_taxonomies() from 
 * twentysixteen/inc/template-tags.php so that it works for `sparkly_review`'s
 * taxonomies
 */
function twentysixteen_entry_taxonomies() {
	if ( 'sparkly_review' === get_post_type() ) {
		$categories_list = get_the_term_list(get_the_id(), 'sparkly_genres',  '', ', ');
	} else {
		$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'twentysixteen' ) );
	}
	if ( $categories_list && twentysixteen_categorized_blog() ) {
		printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
			_x( 'Categories', 'Used before category names.', 'twentysixteen' ),
			$categories_list
		);
	}

	if ( 'sparkly_review' == get_post_type() ) {
		$tags_list = get_the_term_list(get_the_id(), 'sparkly_authors',  '', ', ');
	} else {
		$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'twentysixteen' ) );
	}
	if ( $tags_list ) {
		printf( '<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
			_x( 'Tags', 'Used before tag names.', 'twentysixteen' ),
			$tags_list
		);
	}
}


/******************************************************************************
 * 4. OTHER MODIFICATIONS.
 */


/**
 * Add our theme's CSS file.
 */
function sparkly_enqueue_styles() {

	$parent_style = 'twentysixteen-style';

	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( $parent_style ),
		wp_get_theme()->get('Version')
	);
}
add_action( 'wp_enqueue_scripts', 'sparkly_enqueue_styles' );


/**
 * Modify what's displayed in the archive.php template, at the top of the page.
 * We want to tweak the title for the Blog and Reading pages.
 */ 
function sparkly_modify_archive_title( $title ) {
	if ($title == 'Archives: Reviews') {
		return 'Reading';
	} elseif ($title == 'Archives') {
		return 'Blog';
	} else {
		return $title;
	}
}
add_filter( 'get_the_archive_title', 'sparkly_modify_archive_title', 10, 1 );

?>
