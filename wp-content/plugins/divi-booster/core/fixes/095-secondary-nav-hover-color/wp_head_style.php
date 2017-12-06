<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>

#top-header #et-info-phone a:hover, 
#top-header #et-info a:hover span#et-info-phone, 
#top-header #et-info a:hover span#et-info-email, 
#top-header #et-info #db-info-text a:hover, 
#top-header .et-social-icon a:hover { 
	color: <?php echo htmlentities(@$option['hovercol']); ?> !important; 
}
#top-header #et-info #db-info-text a:hover {
	-webkit-transition: color .5s;
	-moz-transition: color .5s;
	transition: color .5s;
}