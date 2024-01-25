<?php
/**
 * This file adds the Home Page to the Optimal Theme.
 *
 * @author Appfinite
 * @package Optimal
 * @subpackage Customizations
 */

add_action( 'genesis_meta', 'optimal_front_page_genesis_meta' );
/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 *
 */
function optimal_front_page_genesis_meta() {

	if ( is_active_sidebar( 'front-page-1' ) || is_active_sidebar( 'front-page-2' ) || is_active_sidebar( 'front-page-3' ) || is_active_sidebar( 'front-page-4' ) || is_active_sidebar( 'front-page-5' ) || is_active_sidebar( 'front-page-6' ) || is_active_sidebar( 'front-page-7' ) || is_active_sidebar( 'front-page-8' ) || is_active_sidebar( 'front-page-9' ) || is_active_sidebar( 'front-page-10' ) || is_active_sidebar( 'front-page-11' ) ) {

		//* Enqueue scripts
		add_action( 'wp_enqueue_scripts', 'optimal_enqueue_optimal_script' );
		function optimal_enqueue_optimal_script() {

			//wp_enqueue_script( 'optimal-script', get_bloginfo( 'stylesheet_directory' ) . '/js/home.js', array( 'jquery' ), '1.0.0' );
			wp_enqueue_script( 'localScroll', get_stylesheet_directory_uri() . '/js/jquery.localScroll.min.js', array( 'scrollTo' ), '1.2.8b', true );
			wp_enqueue_script( 'scrollTo', get_stylesheet_directory_uri() . '/js/jquery.scrollTo.min.js', array( 'jquery' ), '1.4.5-beta', true );

		}
		
		/*
		// Enqueue parallax script
		add_action( 'wp_enqueue_scripts', 'optimal_enqueue_parallax_script' );
		function optimal_enqueue_parallax_script() {

			if ( ! wp_is_mobile() ) {

				wp_enqueue_script( 'parallax-script', get_bloginfo( 'stylesheet_directory' ) . '/js/parallax.js', array( 'jquery' ), '1.0.0' );

			}

		
		}*/
		

		//* Add front-page body class
		add_filter( 'body_class', 'optimal_body_class' );
		function optimal_body_class( $classes ) {

   			$classes[] = 'front-page';
  			return $classes;

		}

		//* Remove breadcrumbs
		remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

		//* Add homepage widgets
		add_action( 'genesis_after_header', 'optimal_front_page_top_widgets' );
		add_action( 'genesis_after_header', 'optimal_front_page_mid_widgets' );
		add_action( 'genesis_after_header', 'optimal_front_page_bottom_widgets' );
		
		$journal = get_option( 'optimal_journal_setting', 'true' );
		
				if ( $journal === 'true' ) {
		
					//* Add opening markup for blog section
					add_action( 'genesis_before_loop', 'optimal_front_page_blog_open' );
		
					//* Add closing markup for blog section
					add_action( 'genesis_after_loop', 'optimal_front_page_blog_close' );
		
				} else {
				
					//* Force full width content layout
					add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );
		
					//* Remove the default Genesis loop
					remove_action( 'genesis_loop', 'genesis_do_loop' );
		
					//* Remove .site-inner
					add_filter( 'genesis_markup_site-inner', '__return_null' );
					add_filter( 'genesis_markup_content-sidebar-wrap_output', '__return_false' );
					add_filter( 'genesis_markup_content', '__return_null' );
					remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
		
				}		

		//* Add featured-section body class
		if ( is_active_sidebar( 'front-page-1' ) ) {

			//* Add image-section-start body class
			add_filter( 'body_class', 'optimal_featured_body_class' );
			function optimal_featured_body_class( $classes ) {

				$classes[] = 'featured-section';				
				return $classes;

			}

		}

	}

}

//* Add markup for front page widgets
function optimal_front_page_top_widgets() {

	genesis_widget_area( 'front-page-1', array(
		'before' => '<div id="front-page-1" class="front-page-1"><div class="fp1 image-section"><div class="flexible-widgets widget-area fadeup-effect"><div class="wrap">',
		'after'  => '</div></div></div></div>',
	) );
	
	genesis_widget_area( 'front-page-2', array(
		'before' => '<div id="front-page-2" class="front-page-2"><div class="solid-section"><div class="flexible-widgets widget-area fadeup-effect"><div class="wrap">',
		'after'  => '</div></div></div></div>',
	) );
	
}
	
//* Add markup for front page widgets
function optimal_front_page_mid_widgets() {

	if ( is_active_sidebar( 'front-page-3' ) || is_active_sidebar( 'front-page-4' ) || is_active_sidebar( 'front-page-5' ) ) {
	
	echo '<div class="front-page-features"><div class="wrap inner">';

	genesis_widget_area( 'front-page-3', array(
		'before' => '<div id="front-page-3" class="front-page-3 one-third first"><div class="solid-section"><div class="flexible-widgets widget-area fadeup-effect"><div class="wrap">',
		'after'  => '</div></div></div></div>',
	) );
	
	genesis_widget_area( 'front-page-4', array(
		'before' => '<div id="front-page-4" class="front-page-4 one-third"><div class="solid-section"><div class="flexible-widgets widget-area fadeup-effect"><div class="wrap">',
		'after'  => '</div></div></div></div>',
	) );
	
	genesis_widget_area( 'front-page-5', array(
		'before' => '<div id="front-page-5" class="front-page-5 one-third"><div class="solid-section"><div class="flexible-widgets widget-area fadeup-effect"><div class="wrap">',
		'after'  => '</div></div></div></div>',
	) );

	echo '</div><!-- end .wrap --></div><!-- end .front-page-features -->';
	
	}
	
}

//* Add markup for front page widgets
function optimal_front_page_bottom_widgets() {
	
	genesis_widget_area( 'front-page-6', array(
		'before' => '<div id="front-page-6" class="front-page-6"><div class="solid-section"><div class="flexible-widgets widget-area fadeup-effect"><div class="wrap">',
		'after'  => '</div></div></div></div>',
	) );
	
	genesis_widget_area( 'front-page-7', array(
		'before' => '<div id="front-page-7" class="front-page-7"><div class="image-section"><div class="flexible-widgets widget-area fadeup-effect"><div class="wrap">',
		'after'  => '</div></div></div></div>',
	) );
	
		if ( is_active_sidebar( 'front-page-8' ) || is_active_sidebar( 'front-page-9' ) ) {
			
			echo '<div class="fp-content"><div class="wrap">';
			
				genesis_widget_area( 'front-page-8', array(
					'before' => '<div id="front-page-8" class="front-page-8"><div class="solid-section"><div class="flexible-widgets widget-area fadeup-effect"><div class="wrap">',
					'after'  => '</div></div></div></div>',
				) );
				
				genesis_widget_area( 'front-page-9', array(
					'before' => '<div id="front-page-9" class="front-page-9"><div class="solid-section"><div class="flexible-widgets widget-area fadeup-effect"><div class="wrap">',
					'after'  => '</div></div></div></div>',
				) );
		
			echo '</div><!-- end .wrap --></div><!-- end .fp-content -->';
			
			}
	
	genesis_widget_area( 'front-page-10', array(
		'before' => '<div id="front-page-10" class="front-page-10"><div class="solid-section"><div class="flexible-widgets widget-area fadeup-effect"><div class="wrap">',
		'after'  => '</div></div></div></div>',
	) );
	
	genesis_widget_area( 'front-page-11', array(
		'before' => '<div id="front-page-11" class="front-page-11"><div class="image-section"><div class="flexible-widgets widget-area fadeup-effect"><div class="wrap">',
		'after'  => '</div></div></div></div>',
	) );
	
}

//* Add opening markup for blog section
function optimal_front_page_blog_open() {

	$journal_text = get_option( 'optimal_journal_text', __( 'Latest From the Blog', 'optimal' ) );
	
	if ( 'posts' == get_option( 'show_on_front' ) ) {

		echo '<div id="journal" class="widget-area"><div class="wrap">';

		if ( ! empty( $journal_text ) ) {

			echo '<h2 class="widgettitle widget-title journal-title">' . $journal_text . '</h2><hr>';

		}

	}

}

//* Add closing markup for blog section
function optimal_front_page_blog_close() {

	if ( 'posts' == get_option( 'show_on_front' ) ) {

		echo '</div></div>';

	}

}

genesis();