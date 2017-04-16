<?php
/**
 * Template Name: Truckee Events Template
 * This file is the basic wrapper template for all the views if 'Truckee Events Template'
 * is selected in Events -> Settings -> Template -> Events Template.
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

get_header();

/**
 * No idea why, but if we don't include a divi section, the loop totally fails
 * to load. So this is totally empty. And hidden w/CSS. Whatever.
 */
echo do_shortcode('[et_pb_section global_module="1355"]');

?>
<div id="tribe-events-pg-template" class="tribe-event-pg-template">
	<?php tribe_events_before_html(); ?>
	<?php tribe_get_view(); ?>
	<?php tribe_events_after_html(); ?>
</div> <!-- #tribe-events-pg-template -->
<?php
get_footer();
