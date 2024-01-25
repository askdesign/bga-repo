<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'optimal', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'optimal' ) );

//* Add Image upload and Color select to WordPress Theme Customizer
require_once( get_stylesheet_directory() . '/lib/customize.php' );

//* Include Customizer CSS
include_once( get_stylesheet_directory() . '/lib/output.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Optimal' );
define( 'CHILD_THEME_URL', 'http://www.appfinite.com/shop/optimal' );
define( 'CHILD_THEME_VERSION', '1.0.0' );

//* Enqueue Scripts
add_action( 'wp_enqueue_scripts', 'optimal_enqueue_scripts_styles' );
function optimal_enqueue_scripts_styles() {
	wp_enqueue_script( 'optimal-fadeup-script', get_stylesheet_directory_uri() . '/js/fadeup.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,600i,700,700i,900,900i,900i|Alegreya:400,400i,700,700i,900,900i', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_script( 'optimal-global', get_bloginfo( 'stylesheet_directory' ) . '/js/global.js', array( 'jquery' ), '1.0.0' );

}

//* Add Font Awesome Support
add_action( 'wp_enqueue_scripts', 'enqueue_font_awesome' );
function enqueue_font_awesome() {
	wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css', array(), '4.5.0' );
}

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

//* Add accessibility support
add_theme_support( 'genesis-accessibility', array( 'drop-down-menu', 'search-form', 'skip-links' ) );

//* Add WooCommerce Support
add_theme_support( 'genesis-connect-woocommerce' );

//* Disables Default WooCommerce CSS
add_filter( 'woocommerce_enqueue_styles', 'jk_dequeue_styles' );
function jk_dequeue_styles( $enqueue_styles ) {
	unset( $enqueue_styles['woocommerce-general'] );	// Remove the gloss
	unset( $enqueue_styles['woocommerce-layout'] );		// Remove the layout
	return $enqueue_styles;
}

//* Load Custom WooCommerce style sheet
function wp_enqueue_woocommerce_style(){
	wp_register_style( 'custom-woocommerce', get_stylesheet_directory_uri() . '/woocommerce/css/woocommerce.css' );
	
	if ( class_exists( 'woocommerce' ) ) {
		wp_enqueue_style( 'custom-woocommerce' );
	}
}
add_action( 'wp_enqueue_scripts', 'wp_enqueue_woocommerce_style' );


// Change number or products per row to 4
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 4; // 4 products per row
	}
}

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'flex-height'     => true,
	'width'           => 300,
	'height'          => 60,
	'header-selector' => '.site-title a',
	'header-text'     => false,
) );

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'footer-widgets',
	'footer',
) );

//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

//* Add new image sizes
add_image_size( 'featured-content-lg', 1200, 600, TRUE );
add_image_size( 'featured-content-sm', 600, 400, TRUE );
add_image_size( 'featured-content-th', 740, 340, TRUE );

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );

//* Unregister the header right widget area
unregister_sidebar( 'header-right' );

//* Reposition the primary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

//* Remove output of primary navigation right extras
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_before_header', 'genesis_do_subnav', 5 );

//* Reposition entry meta in entry header
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'genesis_post_info', 8 );

//* Customize entry meta in the entry header
add_filter( 'genesis_post_info', 'optimal_entry_meta_header' );
function optimal_entry_meta_header($post_info) {

	$post_info = '[post_date] <span class="entry-by">by</span> [post_author_posts_link] [post_edit]';
	return $post_info;

}

//* Modify the Genesis content limit read more link
add_filter( 'get_the_content_more_link', 'optimal_read_more_link' );
function optimal_read_more_link() {
	return '... <p><a class="more-link" href="' . get_permalink() . '">Read More →</a></p>';
}

//* Add featured image above the entry content
add_action( 'genesis_entry_header', 'optimal_featured_photo', 5 );
function optimal_featured_photo() {

	if ( is_attachment() || ! genesis_get_option( 'content_archive_thumbnail' ) )
		return;

	if ( is_singular() && $image = genesis_get_image( array( 'format' => 'url', 'size' => genesis_get_option( 'image_size' ) ) ) ) {
		printf( '<div class="featured-image"><img src="%s" alt="%s" class="entry-image"/></div>', $image, the_title_attribute( 'echo=0' ) );
	}

}

/*//* Add support for 1-column footer widget area
add_theme_support( 'genesis-footer-widgets', 1 );*/

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Add support for footer menu
add_theme_support ( 'genesis-menus' , array ( 'primary' => 'Primary Navigation Menu', 'secondary' => 'Secondary Navigation Menu', 'footer' => 'Footer Navigation Menu' ) );

//* Hook menu in footer
add_action( 'genesis_footer', 'optimal_footer_menu', 7 );
function optimal_footer_menu() {
	printf( '<nav %s>', genesis_attr( 'nav-footer' ) );
	wp_nav_menu( array(
		'theme_location' => 'footer',
		'container'      => false,
		'depth'          => 1,
		'fallback_cb'    => false,
		'menu_class'     => 'genesis-nav-menu',	
	) );
	
	echo '</nav>';
}

// WooCommerce Support for the Genesis Connect Plugin
add_theme_support( 'genesis-connect-woocommerce' );

// Theme Settings init
add_action( 'admin_menu', 'optimal_theme_settings_init', 15 ); 
/** 
 * This is a necessary go-between to get our scripts and boxes loaded 
 * on the theme settings page only, and not the rest of the admin 
 */ 
function optimal_theme_settings_init() { 
    global $_genesis_admin_settings; 
     
    add_action( 'load-' . $_genesis_admin_settings->pagehook, 'optimal_add_portfolio_settings_box', 20 ); 
} 

// Add Portfolio Settings box to Genesis Theme Settings 
function optimal_add_portfolio_settings_box() { 
    global $_genesis_admin_settings; 
     
    add_meta_box( 'genesis-theme-settings-optimal-portfolio', __( 'Portfolio Page Settings', 'optimal' ), 'optimal_theme_settings_portfolio',     $_genesis_admin_settings->pagehook, 'main' ); 
}  
	
/** 
 * Adds Portfolio Options to Genesis Theme Settings Page
 */ 	
function optimal_theme_settings_portfolio() { ?>

	<p><?php _e("Display which category:", 'genesis'); ?>
	<?php wp_dropdown_categories(array('selected' => genesis_get_option('optimal_portfolio_cat'), 'name' => GENESIS_SETTINGS_FIELD.'[optimal_portfolio_cat]', 'orderby' => 'Name' , 'hierarchical' => 1, 'show_option_all' => __("All Categories", 'genesis'), 'hide_empty' => '0' )); ?></p>
	
	<p><?php _e("Exclude the following Category IDs:", 'genesis'); ?><br />
	<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[optimal_portfolio_cat_exclude]" value="<?php echo esc_attr( genesis_get_option('optimal_portfolio_cat_exclude') ); ?>" size="40" /><br />
	<small><strong><?php _e("Comma separated - 1,2,3 for example", 'genesis'); ?></strong></small></p>
	
	<p><?php _e('Number of Posts to Show', 'genesis'); ?>:
	<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[optimal_portfolio_cat_num]" value="<?php echo esc_attr( genesis_option('optimal_portfolio_cat_num') ); ?>" size="2" /></p>
	
	<p><span class="description"><?php _e('<b>NOTE:</b> The Portfolio Page displays the "Portfolio Page" image size plus the excerpt or full content as selected below.', 'optimal'); ?></span></p>
	
	<p><?php _e("Select one of the following:", 'genesis'); ?>
	<select name="<?php echo GENESIS_SETTINGS_FIELD; ?>[optimal_portfolio_content]">
		<option style="padding-right:10px;" value="full" <?php selected('full', genesis_get_option('optimal_portfolio_content')); ?>><?php _e("Display post content", 'genesis'); ?></option>
		<option style="padding-right:10px;" value="excerpts" <?php selected('excerpts', genesis_get_option('optimal_portfolio_content')); ?>><?php _e("Display post excerpts", 'genesis'); ?></option>
	</select></p>
	
	<p><label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[optimal_portfolio_content_archive_limit]"><?php _e('Limit content to', 'genesis'); ?></label> <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[optimal_portfolio_content_archive_limit]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[optimal_portfolio_content_archive_limit]" value="<?php echo esc_attr( genesis_option('optimal_portfolio_content_archive_limit') ); ?>" size="3" /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[optimal_portfolio_content_archive_limit]"><?php _e('characters', 'genesis'); ?></label></p>
	
	<p><span class="description"><?php _e('<b>NOTE:</b> Using this option will limit the text and strip all formatting from the text displayed. To use this option, choose "Display post content" in the select box above.', 'genesis'); ?></span></p>
<?php
}	

// Enable shortcodes in widgets
add_filter('widget_text', 'do_shortcode');

//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'front-page-1',
	'name'        => __( 'Front Page 1', 'optimal' ),
	'description' => __( 'This is the front page 1 section.', 'optimal' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-2',
	'name'        => __( 'Front Page 2', 'optimal' ),
	'description' => __( 'This is the front page 2 section.', 'optimal' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-3',
	'name'        => __( 'Front Page 3', 'optimal' ),
	'description' => __( 'This is the front page 3 section.', 'optimal' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-4',
	'name'        => __( 'Front Page 4', 'optimal' ),
	'description' => __( 'This is the front page 4 section.', 'optimal' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-5',
	'name'        => __( 'Front Page 5', 'optimal' ),
	'description' => __( 'This is the front page 5 section.', 'optimal' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-6',
	'name'        => __( 'Front Page 6', 'optimal' ),
	'description' => __( 'This is the front page 6 section.', 'optimal' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-7',
	'name'        => __( 'Front Page 7', 'optimal' ),
	'description' => __( 'This is the front page 7 section.', 'optimal' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-8',
	'name'        => __( 'Front Page 8', 'optimal' ),
	'description' => __( 'This is the front page 8 section.', 'optimal' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-9',
	'name'        => __( 'Front Page 9', 'optimal' ),
	'description' => __( 'This is the front page 9 section.', 'optimal' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-10',
	'name'        => __( 'Front Page 10', 'optimal' ),
	'description' => __( 'This is the front page 10 section.', 'optimal' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-11',
	'name'        => __( 'Front Page 11', 'optimal' ),
	'description' => __( 'This is the front page 11 section.', 'optimal' ),
) );

//* ASK DESIGN CUSTOMIZATIONS 
//* CUSTOM FIELDS - Artist Call

add_action( 'genesis_before_entry', 'custom_field_before_post_title', 15 );
/**
 * @author    Brad Dalton
 * @example   http://wpsites.net/
 * @copyright 2014 WP Sites
 */
function custom_field_before_post_title() {
if ( is_category('artist-calls-and-info-current') && genesis_get_custom_field('artist_call') ) :
echo '<div class="artistcall">'. genesis_get_custom_field('artist_call') .'</div>';
endif;
}
//* end of above code


// CUSTOM FIELD - Show Dates
add_action( 'genesis_before_entry_content', 'custom_field_before_content', 15 );
/**
 * @author    Brad Dalton
 * @example   http://wpsites.net/
 * @copyright 2014 WP Sites
 */
function custom_field_before_content() {
if ( is_home() OR is_category() OR is_singular(array( 'post', 'page') ) && genesis_get_custom_field('show_dates') ) :
echo '<div class="showdates">'. genesis_get_custom_field('show_dates') .'</div>';
endif;
}
//* end of above code


// SEARCH BOX -
/**
As of Genesis 2.1, the Primary Navigation Extras options have been removed for new installs. Here's how to reinstall
*/

add_filter( 'wp_nav_menu_items', 'theme_menu_extras', 10, 2 );
/**
 * Filter menu items, appending either a search form or today's date.
 *
 * @param string   $menu HTML string of list items.
 * @param stdClass $args Menu arguments.
 *
 * @return string Amended HTML string of list items.
 */
function theme_menu_extras( $menu, $args ) {

	//* Change 'primary' to 'secondary' to add extras to the secondary navigation menu
	if ( 'secondary' !== $args->theme_location )
		return $menu;

	//* Uncomment this block to add a search form to the navigation menu

	ob_start();
	get_search_form();
	$search = ob_get_clean();
	$menu  .= '<li class="left search">' . $search . '</li>';


	//* Uncomment this block to add the date to the navigation menu
	/*
	$menu .= '<li class="right date">' . date_i18n( get_option( 'date_format' ) ) . '</li>';
	*/

	return $menu;

}

// ** Customize the credits 
add_filter('genesis_footer_creds_text', 'custom_footer_creds_text');
function custom_footer_creds_text() {
    echo '<div class="creds"><p>';
    echo 'Copyright &copy; ';
    echo date('Y');
 	echo ' &middot; Belmont Gallery of Art &middot; <a href="https://appfinite.com/shop/optimal/">Optimal Theme</a> &middot; Built on the WordPress 
	<a href="https://shareasale.com/r.cfm?b=1320631&u=838511&m=41388&urllink=&afftrack=">Genesis Framework</a>&middot; Customization and design by <a href="https://www.askdesign.biz/">ASK Design</a>';	
    echo '</p></div>';
}

