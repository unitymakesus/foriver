<?php
	/* DASHBOARD */
	add_action('wp_dashboard_setup', 'tdm_dash_info');
	if ( ! function_exists ( 'tdm_dash_info' ) ) {
		function tdm_dash_info() {
			global $wp_meta_boxes;
			wp_add_dashboard_widget('tdm_dash', 'Latest from The Divi Magazine', 'tdm_dash_content');
		}
		function tdm_dash_content() {
			$tdm_feed = 'http://www.andrasguseo.com/feed/dashboard.php';
			$feed_extract = file_get_contents( $tdm_feed );
		?>
		<div class="tdm-dash">
			<?php echo $feed_extract; ?>
		</div>
		<?php
		}
	}
?>