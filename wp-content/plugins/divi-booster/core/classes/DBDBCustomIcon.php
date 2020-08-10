<?php

if (!class_exists('DBDBCustomIcon')) {
	
	class DBDBCustomIcon {
		
		protected $id;
		protected $url;		
		
		public function __construct($id, $url='') {
			$this->id = $id;
			$this->url = $url;
		}
		
		public static function setup() {
			add_action('wp_head', array(__CLASS__, 'outputUserCss'));
			add_action('wp_head', array(__CLASS__, 'outputSharedUserJs'));
			add_action('wp_footer', array(__CLASS__, 'outputVisualBuilderJs'));
		}
		
		public static function outputUserCss() {
			echo '<style>'.self::getUserCss().'</style>';
		}
		
		public static function outputSharedUserJs() {
			echo '<script>'.self::getSharedUserJs().'</script>';
		}
		
		public static function outputVisualBuilderJs() {
			if (function_exists('et_fb_enabled') && et_fb_enabled()) {
				echo '<script>jQuery(function($){'.self::getMutationObserverJs().'});</script>';
			}
		}
		
		public function init() {
			$fontSymbolsPriority = apply_filters('DBDBCustomIcon_font_icon_symbols_priority', 50);
			add_filter('et_pb_font_icon_symbols', array($this, 'addToFontSymbols'), $fontSymbolsPriority);
			add_action('admin_head', array($this, 'outputIconPickerCss'));
			add_action('wp_head', array($this, 'outputIconPickerCssFb'));
			add_action('wp_footer', array($this, 'outputIconUpdateJs'));
			add_action('wp_head', array($this, 'db014_user_css_for_custom_button_icon'));
			add_action('wp_head', array($this, 'db014_user_css_for_custom_button_icons'));
			add_action('wp_head', array($this, 'db014_user_css_for_inline_icons'));
			add_action('wp_head', array($this, 'db014_user_css_for_custom_hover_icon'));
			add_action('wp_head', array($this, 'db014_user_css_to_hide_unwanted_icons'));
		}
		
		function db014_user_css_for_custom_button_icons() { ?>
			<style>
			.et_pb_custom_button_icon[data-icon="<?php esc_attr_e($this->id); ?>"]:before, 
			.et_pb_custom_button_icon[data-icon="<?php esc_attr_e($this->id); ?>"]:after {
				background-size: auto 1em;
				background-repeat: no-repeat;
				min-width: 20em;
				height: 100%;
				content: "" !important;
				background-position: left center;
				position: absolute;
				top: 0;
			}
			.et_pb_custom_button_icon[data-icon="<?php esc_attr_e($this->id); ?>"] { 
				overflow: hidden;
			}
			</style>
			<?php
		}

		function db014_user_css_for_inline_icons() { ?>
			<style>
			.et_pb_posts .et_pb_inline_icon[data-icon="<?php esc_attr_e($this->id); ?>"]:before,
			.et_pb_portfolio_item .et_pb_inline_icon[data-icon="<?php esc_attr_e($this->id); ?>"]:before {
				content: '' !important;
				-webkit-transition: all 0.4s;
				-moz-transition: all 0.4s;
				transition: all 0.4s;
			}
			.et_pb_posts .entry-featured-image-url:hover .et_pb_inline_icon[data-icon="<?php esc_attr_e($this->id); ?>"] img,
			.et_pb_portfolio_item .et_portfolio_image:hover .et_pb_inline_icon[data-icon="<?php esc_attr_e($this->id); ?>"] img { 
				margin-top:0px; transition: all 0.4s;
			}
			.et_pb_posts .entry-featured-image-url .et_pb_inline_icon[data-icon="<?php esc_attr_e($this->id); ?>"] img, 
			.et_pb_portfolio_item .et_portfolio_image .et_pb_inline_icon[data-icon="<?php esc_attr_e($this->id); ?>"] img { 
				margin-top: 14px; 
			}
			.et_pb_dmb_breadcrumbs a:first-child .db014_custom_hover_icon {
				position: relative !important;
				left: 0%; 
				transform: none;
				vertical-align: middle;
				margin-right: 8px;
			}
			.et_pb_dmb_breadcrumbs li .db014_custom_hover_icon {
				position: relative !important;
				left: 0%; 
				transform: none;
				vertical-align: middle;
				margin-right: 8px;
				margin-left: 4px;
			}
			</style>
			<?php
		}

		function db014_user_css_for_custom_hover_icon() { ?>
			<style>
			.db014_custom_hover_icon { 
				width:auto !important; 
				max-width:32px !important; 
				min-width:0 !important;
				height:auto !important; 
				max-height:32px !important; 
				min-height:0 !important;
				position:absolute;
				top:50%;
				left:50%;
				-webkit-transform: translate(-50%,-50%); 
				-moz-transform: translate(-50%,-50%); 
				-ms-transform: translate(-50%,-50%); 
				transform: translate(-50%,-50%); 
			}
			</style>
			<?php	
		}

		function db014_user_css_to_hide_unwanted_icons() { ?>
			<style>
			.et_pb_gallery .et_pb_gallery_image .et_pb_inline_icon[data-icon="<?php esc_attr_e($this->id); ?>"]:before,
			.et_pb_blog_grid .et_pb_inline_icon[data-icon="<?php esc_attr_e($this->id); ?>"]:before,
			.et_pb_image .et_pb_image_wrap .et_pb_inline_icon[data-icon="<?php esc_attr_e($this->id); ?>"]:before,
			.et_pb_dmb_breadcrumbs > ol > li > a:first-child[data-icon="<?php esc_attr_e($this->id); ?>"]:before,
			.et_pb_dmb_breadcrumbs > ol > li[data-icon="<?php esc_attr_e($this->id); ?>"]:before
			{ 
				display:none !important; 
			}
			span.db-custom-icon {
			   color: rgba(0,0,0,0) !important;
			}
			</style>
			<?php
		}
		
		function db014_user_css_for_custom_button_icon() { 
			$id = $this->id;
			$url = $this->url;
			$icon = '.et_pb_custom_button_icon[data-icon="'.esc_html($id).'"]';
			$bg_img = empty($url)?'none':"url('".esc_html($url)."')";
			echo <<<END
			<style>
			$icon:before, 
			$icon:after {
				background-image: $bg_img;		
			}
			</style>
END;

			$is_svg = preg_match('#\.svg(\?[^.]*)?$#', $url);
			if ($is_svg) {
				// IE SVG background-size (as "auto" not supported) 
				// - width = half the 2em padding allocated for icon, and 50% height of button
				echo <<<END
				<style>
				body.ie $icon:before, 
				body.ie $icon:after {
					background-size: 1em 50%; 	
				}
				</style>
END;
			}
		}
		
		public function addToFontSymbols($fontSymbols) {
			$fontSymbols[] = $this->id;
			return $fontSymbols;
		}
		
		public function outputIconUpdateJs() { ?>
			<script>
			jQuery(function($){
				<?php if (!function_exists('et_fb_enabled') || !et_fb_enabled()) { ?>
				setTimeout(
					function() { 
						db014_update_icon(<?php echo json_encode($this->id); ?>, <?php echo json_encode($this->url); ?>);
					}, 100
				);
				<?php } ?>
				$(document).on('db_vb_custom_icons_updated', function () {
					db014_update_icon(<?php echo json_encode($this->id); ?>, <?php echo json_encode($this->url); ?>);
				});
				$('#et-main-area, .et_pb_module').on('mouseover mouseout', function () {
					setTimeout(
						function() { 
							db014_update_icon(<?php echo json_encode($this->id); ?>, <?php echo json_encode($this->url); ?>); 
						},
						0
					);
				});
			});
			</script>
			<?php
		}		
		
		public function outputIconPickerCssFb() {
			if (function_exists('et_fb_enabled') && et_fb_enabled()) {
				$this->outputIconPickerCss();
			}
		}
		
		public function outputIconPickerCss() {
			echo '<style>'.$this->getIconPickerCss().'</style>';
		}
		
		public function getIconPickerCss() {
			$url = esc_html($this->url);
			$id = esc_attr($this->id);
			return <<<END
			.et-fb-option--select-icon li[data-icon="{$id}"]:after,
			.et-pb-option--select_icon li[data-icon="{$id}"]:before,
			.et-pb-option ul.et_font_icon li[data-icon="{$id}"]::before { 
				background: url('{$url}') no-repeat center center; 
				background-size: cover; 
				content: 'a' !important; 
				width: 16px !important; 
				height: 16px !important; 
				color: rgba(0,0,0,0) !important; 
			}
END;
		}		
		
		protected static function getSharedUserJs() {
			return <<<'END'
			function db014_update_icon(icon_id, icon_url) {
				db014_update_icons(jQuery(document), icon_id, icon_url);
				var $app_frame = jQuery("#et-fb-app-frame");
				if ($app_frame) {
					db014_update_icons($app_frame.contents(), icon_id, icon_url);
				}
			}
			
			function db014_update_icons(doc, icon_id, icon_url) { 
				db014_update_custom_icons(doc, icon_id, icon_url);
				db014_update_custom_inline_icons(doc, icon_id, icon_url);
			}
	
			function db014_update_custom_icons(doc, icon_id, icon_url) {
				var $custom_icons = doc.find('.et-pb-icon:contains("'+icon_id+'")');	
				var icon_visible = (icon_url !== '');
				var $icons = $custom_icons.filter(function(){ return jQuery(this).text() == icon_id; }); 
				$icons.addClass('db-custom-icon');
				$icons.html('<img class="dbdb-custom-icon-img" src="'+icon_url+'"/>');
				$icons.toggle(icon_visible); 
			}
			
			function db014_update_custom_inline_icons(doc, icon_id, icon_url) {
				var $custom_inline_icons = doc.find('.et_pb_inline_icon[data-icon="'+icon_id+'"]');
				var icon_visible = (icon_url !== '');
				var $icons_inline = $custom_inline_icons.filter(function(){ return jQuery(this).attr('data-icon') == icon_id; });
				$icons_inline.addClass('db-custom-icon');
				$icons_inline.each(function(){
					if (jQuery(this).children('.db014_custom_hover_icon').length === 0) {
						if (jQuery(this).closest('.et_pb_dmb_breadcrumbs').length === 0) {
							jQuery(this).html('<img class="db014_custom_hover_icon"/>');
						} else {
							jQuery(this).prepend(jQuery('<img class="db014_custom_hover_icon"/>'));
						}
					}
					jQuery(this).children('.db014_custom_hover_icon').attr('src', icon_url);
				});
				$icons_inline.toggle(icon_visible);
			}
END;
		}
		
		protected static function getMutationObserverJs() { 
			return <<<'END'
			db014_watch_for_changes_that_might_update_icons();
			
			function db014_watch_for_changes_that_might_update_icons() {
				var target = document.getElementById('et-fb-app'); 
				var observer = new MutationObserver(function(mutations) {
					mutations.forEach(function(mutation) {
						if (mutation.type === 'characterData') {
							$(document).trigger('db_vb_custom_icons_updated');
						} else if (mutation.type === 'childList') {
							if (db014_may_contain_icons(mutation.target)) {
								$(document).trigger('db_vb_custom_icons_updated');
							}
						} else if (mutation.type === 'attributes') {
							$(document).trigger('db_vb_custom_icons_updated');
						}

					});
				});
				observer.observe(
					document.getElementById('et-fb-app'), 
					{ 
						attributes: true, 
						attributeFilter: ["class"],
						childList: true, 
						characterData: true,
						subtree: true
					}
				);
			}
			
			function db014_may_contain_icons(target) {
				if (target.className === undefined) { 
					return false; 
				}
				var classes = target.className;
				if (classes.search === undefined) { 
					return false; 
				}
				if (classes.search(/(et-pb-icon|et_pb_inline_icon|et-fb-root-ancestor|et_pb_root--vb|et-fb-post-content|et_pb_section|et_pb_row|et_pb_column)/i) !== -1) {
					return true;
				}
				return false;
			}
END;
		}
		
		protected static function getUserCss() { 
			return <<<'END'
			.et-pb-icon.db-custom-icon {
				line-height: unset;
			}
			.db-custom-icon img { 
				height: 1em;
			}
			.et_pb_blurb_position_left .db-custom-icon, 
			.et_pb_blurb_position_right .db-custom-icon {
				width: 1em;
				display: block;
			}
			.et_pb_blurb_position_left .dbdb-custom-icon-img,
			.et_pb_blurb_position_right .dbdb-custom-icon-img {
				height: auto;    
				vertical-align: top;
			}
END;
		}
	}
}