<?php

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
	$theme_version = et_get_theme_version();
	wp_enqueue_style('divi/style', get_template_directory_uri() . '/style.css', false, $theme_version);
	wp_enqueue_style('foriver/style', get_stylesheet_directory_uri() . '/stylesheets/main.css', false, NULL);
  wp_enqueue_script('foriver/scripts', get_stylesheet_directory_uri() . '/scripts/main.js', false, $theme_version, true);

	// Replace ET scripts
	$current_page_id = apply_filters( 'et_is_ab_testing_active_post_id', get_the_ID() );
	wp_enqueue_script('foriver/et-builder-modules-script', get_stylesheet_directory_uri() . '/scripts/frontend-builder-scripts.js', apply_filters( 'et_pb_frontend_builder_scripts_dependencies', array( 'jquery', 'et-jquery-touch-mobile' ) ), $theme_version, true);
	wp_localize_script('foriver/et-builder-modules-script', 'et_pb_custom', array(
		'ajaxurl'                => admin_url( 'admin-ajax.php' ),
		'images_uri'             => get_template_directory_uri() . '/images',
		'builder_images_uri'     => ET_BUILDER_URI . '/images',
		'et_frontend_nonce'      => wp_create_nonce( 'et_frontend_nonce' ),
		'subscription_failed'    => esc_html__( 'Please, check the fields below to make sure you entered the correct information.', 'et_builder' ),
		'et_ab_log_nonce'        => wp_create_nonce( 'et_ab_testing_log_nonce' ),
		'fill_message'           => esc_html__( 'Please, fill in the following fields:', 'et_builder' ),
		'contact_error_message'  => esc_html__( 'Please, fix the following errors:', 'et_builder' ),
		'invalid'                => esc_html__( 'Invalid email', 'et_builder' ),
		'captcha'                => esc_html__( 'Captcha', 'et_builder' ),
		'prev'                   => esc_html__( 'Prev', 'et_builder' ),
		'previous'               => esc_html__( 'Previous', 'et_builder' ),
		'next'                   => esc_html__( 'Next', 'et_builder' ),
		'wrong_captcha'          => esc_html__( 'You entered the wrong number in captcha.', 'et_builder' ),
		'is_builder_plugin_used' => et_is_builder_plugin_active(),
		'is_divi_theme_used'     => function_exists( 'et_divi_fonts_url' ),
		'widget_search_selector' => apply_filters( 'et_pb_widget_search_selector', '.widget_search' ),
		'is_ab_testing_active'   => et_is_ab_testing_active(),
		'page_id'                => $current_page_id,
		'unique_test_id'         => get_post_meta( $current_page_id, '_et_pb_ab_testing_id', true ),
		'ab_bounce_rate'         => '' !== get_post_meta( $current_page_id, '_et_pb_ab_bounce_rate_limit', true ) ? get_post_meta( $current_page_id, '_et_pb_ab_bounce_rate_limit', true ) : 5,
		'is_cache_plugin_active' => false === et_pb_detect_cache_plugins() ? 'no' : 'yes',
		'is_shortcode_tracking'  => get_post_meta( $current_page_id, '_et_pb_enable_shortcode_tracking', true ),
	) );

}, 100);


// Dequeue some Divi scripts
add_action('wp_print_scripts', function() {
	// Dequeue and replace
	wp_dequeue_script('et-builder-modules-script');

	// Dequeue Waypoints on Home
	if (is_home() || is_front_page()) {
		// wp_dequeue_script('waypoints');
	}
}, 100);

/* SQUARE GALLERY IMAGES */
add_filter( 'et_pb_gallery_image_height', 'gallery_size_h' );
add_filter( 'et_pb_gallery_image_width', 'gallery_size_w' );
function gallery_size_h($height) {
	return '400';
}
function gallery_size_w($width) {
	return '400';
}



/**
 * Used in list loop, displays the date headers between events in the loop when the month / year has changed
 *
 **/
function divi_tribe_events_list_the_date_headers() {

	/* Month and year separators (on every month and year change) */

	$show_headers = apply_filters( 'tribe_events_list_show_date_headers', true );

	$html = '';

	if ( $show_headers ) {

		global $post, $wp_query;

		$event_year        = tribe_get_start_date( $post, false, 'Y' );
		$event_month       = tribe_get_start_date( $post, false, 'm' );
		$month_year_format = tribe_get_date_option( 'monthAndYearFormat', 'F Y' );

		if ( $wp_query->current_post > 0 ) {
			$prev_post = $wp_query->posts[ $wp_query->current_post - 1 ];
			$prev_event_year = tribe_get_start_date( $prev_post, false, 'Y' );
			$prev_event_month = tribe_get_start_date( $prev_post, false, 'm' );
		}


		/*
		 * If the event month changed since the last event in the loop,
		 * or is the same month but the year changed.
		 *
		 */
		if ( $wp_query->current_post === 0 || ( $prev_event_month != $event_month || ( $prev_event_month == $event_month && $prev_event_year != $event_year ) ) ) {
			$html .= sprintf( "<span class='tribe-events-list-separator-month'><span>%s</span></span>", tribe_get_start_date( $post, false, $month_year_format ) );
		}

		echo apply_filters( 'tribe_events_list_the_date_headers', $html, $event_month, $event_year );
	}
}


/*
* The Events Calendar Open gCal Links in New Windows
* @version 3.10
*/
add_action( 'tribe_events_single_event_after_the_meta', 'tribe_open_gcal_new_tab' );
function tribe_open_gcal_new_tab() {
  echo '<script>
	  /* Open gCal Links in New Windows */
	  	jQuery("a.tribe-events-gcal")
	  		.addClass("external")
	  		.click( function() {
	  			window.open(this.href);
	  			return false;
	  		});
  </script>';
}
