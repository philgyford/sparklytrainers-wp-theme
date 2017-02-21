<?php
/**
 * Front page.
 *
 * For when 'A static page' is selected as the Front Page.
 * We want to combine both Posts and our custom post type - Reviews
 *
 * This is based on twentysixteen/index.php
 */

$paged = (get_query_var('page')) ? get_query_var('page') : 1;

// Get both Posts and Reviews:
$args = array(
	'post_type' => array('post', 'sparkly_review'),
	'orderby' => 'date',
	'order' => 'DESC',
	'posts_per_page' => 5,
	'paged' => $paged
);
$the_query = new WP_Query( $args );

// Pagination fix - to make the pagination appear.
$temp_query = $wp_query;
$wp_query   = NULL;
$wp_query   = $the_query;


get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( $the_query->have_posts() ) : ?>

			<?php if ( $the_query->is_home() && ! $the_query->is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>

			<?php
			// Start the loop.
			while ( $the_query->have_posts() ) : $the_query->the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_format() );

			// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'twentysixteen' ),
				'next_text'          => __( 'Next page', 'twentysixteen' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>',
			) );

			// Pagination fix - to make the pagination appear.
			wp_reset_postdata();

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

