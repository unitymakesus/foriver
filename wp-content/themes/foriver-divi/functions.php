<?php

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
	$theme_version = et_get_theme_version();
	wp_enqueue_style('divi/style', get_template_directory_uri() . '/style.css', false, $theme_version);
	wp_enqueue_style('foriver/style', get_stylesheet_directory_uri() . '/stylesheets/main.css', false, '1');
  wp_enqueue_script('foriver/scripts', get_stylesheet_directory_uri() . '/scripts/main.js', false, $theme_version, true);
}, 100);

/**
 * Used in list loop
 * Displays the date headers between events in the loop when the month / year has changed
 *
 */
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

/**
 * Remove incorrect past notice for current and upcoming event single views.
 */
add_filter('tribe_the_notices', function ($html, $notices) {
	if (!tribe_is_past_event() && strpos($html, 'This event has passed.')) {
		$html = '';
	}

	return $html;
}, 10, 2 );
