<?php

/*

Plugin Name: Divi Dashboard Welcome

Plugin URL: https://divilife.com/product/divi-dashboard-welcome/

Description: A plugin to modify the dashboard welcome message using the Divi Builder.

Version: 1.2

Author: Divi Life — by Tim Strifler

Author URI: https://divilife.com

*/





/**

 * Hide default welcome dashboard message and and create a custom one

 *

 * @access      public

 * @since       1.0 

 * @return      void

*/




function ts_divi_dashboard_welcome() {



	?>

<script type="text/javascript">

/* Hide default welcome message */

jQuery(document).ready( function($) 

{

	$('div.welcome-panel-content').hide();

});

</script>
<iframe id="divi-dashboard-welcome-iframe" style="margin-top: -24px; margin-bottom: -9px; margin-left: -1%;" marginheight="0" frameborder="0" src="<?php echo get_home_url(); ?>/divi-dashboard-welcome-screen" width="102%" height="600px"></iframe>



<?php

}



add_action( 'welcome_panel', 'ts_divi_dashboard_welcome' );



function ts_divi_dashboard_welcome_hide_admin($bool) {
if ( is_page( 'divi-dashboard-welcome-screen' ) ) :
return false;
else :
return $bool;
endif;
}
add_filter('show_admin_bar', 'ts_divi_dashboard_welcome_hide_admin');


add_action('wp_trash_post', 'restrict_post_deletion', 10, 1);
add_action('before_delete_post', 'restrict_post_deletion', 10, 1);
function restrict_post_deletion($post_id) {
    if( $post_id == 609 ) {
      exit('Oops! This page can not be deleted! It is required for displaying the Dashboard Welcome. If you need to remove this page, you can do so by first deactivating the plugin "Divi Dashboard Welcome", then return to pages and attempt again. Please hit the back button to return to the previous page.');
    }
  }
  
  
add_action('admin_head', 'divi_dashboard_styles');

function divi_dashboard_styles() {
  echo '<style>
    .welcome-panel {
    overflow: hidden;
}
  </style>';
}