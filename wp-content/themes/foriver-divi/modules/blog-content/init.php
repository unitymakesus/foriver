<?php
class CAC_Custom_Module_Blog_Content extends ET_Builder_Module {
  function init() {
    $this->name = esc_html__( 'Blog - Content', 'et_builder' );
    $this->slug = 'et_pb_blog_content';

    $this->whitelisted_fields = array(
      'background_layout',
      'admin_label',
      'module_id',
      'module_class',
      'use_dropshadow',
    );

    $this->fields_defaults = array(
      'background_layout' => array( 'light' ),
      'use_dropshadow' => array( 'off' ),
    );

    $this->main_css_element = '%%order_class%% .et_pb_post';

    $this->advanced_options = array(
      'fonts' => array(
        'header' => array(
          'label' => esc_html__( 'Header', 'et_builder' ),
          'css' => array(
            'main' => "{$this->main_css_element} h2",
            'important' => 'all',
          ),
        ),
        'meta' => array(
          'label' => esc_html__( 'Meta', 'et_builder' ),
          'css' => array(
            'main' => "{$this->main_css_element} .post-meta",
          ),
        ),
        'body' => array(
          'label' => esc_html__( 'Body', 'et_builder' ),
          'css' => array(
            'line_height' => "{$this->main_css_element} p",
          ),
        ),
      ),
      'border' => array(),
    );

    $this->custom_css_options = array(
    );
  }

  function get_fields() {
    $fields = array(
      'background_layout' => array(
        'label' => esc_html__( 'Text Color', 'et_builder' ),
        'type' => 'select',
        'option_category' => 'color_option',
        'options' => array(
          'light' => esc_html__( 'Dark', 'et_builder' ),
          'dark' => esc_html__( 'Light', 'et_builder' ),
        ),
        'depends_default' => true,
        'description' => esc_html__( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
      ),
      'use_dropshadow' => array(
        'label' => esc_html__( 'Use Dropshadow', 'et_builder' ),
        'type' => 'yes_no_button',
        'option_category' => 'layout',
        'options' => array(
          'off' => esc_html__( 'Off', 'et_builder' ),
          'on' => esc_html__( 'On', 'et_builder' ),
        ),
        'tab_slug' => 'advanced',
        'depends_show_if' => 'off',
      ),
      'disabled_on' => array(
        'label' => esc_html__( 'Disable on', 'et_builder' ),
        'type' => 'multiple_checkboxes',
        'options' => array(
          'phone' => esc_html__( 'Phone', 'et_builder' ),
          'tablet' => esc_html__( 'Tablet', 'et_builder' ),
          'desktop' => esc_html__( 'Desktop', 'et_builder' ),
        ),
        'additional_att' => 'disable_on',
        'option_category' => 'configuration',
        'description' => esc_html__( 'This will disable the module on selected devices', 'et_builder' ),
      ),
      'admin_label' => array(
        'label' => esc_html__( 'Admin Label', 'et_builder' ),
        'type' => 'text',
        'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
      ),
      'module_id' => array(
        'label' => esc_html__( 'CSS ID', 'et_builder' ),
        'type' => 'text',
        'option_category' => 'configuration',
        'tab_slug' => 'custom_css',
        'option_class' => 'et_pb_custom_css_regular',
      ),
      'module_class' => array(
        'label' => esc_html__( 'CSS Class', 'et_builder' ),
        'type' => 'text',
        'option_category' => 'configuration',
        'tab_slug' => 'custom_css',
        'option_class' => 'et_pb_custom_css_regular',
      ),
    );
    return $fields;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    $module_id = $this->shortcode_atts['module_id'];
    $module_class = $this->shortcode_atts['module_class'];
    $background_layout = $this->shortcode_atts['background_layout'];
    $use_dropshadow = $this->shortcode_atts['use_dropshadow'];

    global $paged;

    $module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

    $container_is_closed = false;

    // remove all filters from WP audio shortcode to make sure current theme doesn't add any elements into audio module
    // remove_all_filters( 'wp_audio_shortcode_library' );
    // remove_all_filters( 'wp_audio_shortcode' );
    // remove_all_filters( 'wp_audio_shortcode_class');

    $args = array( 'p' => (int) get_the_id() );

    ob_start();

    query_posts( $args );

    if ( have_posts() ) {
      while ( have_posts() ) {
        the_post();

        $post_format = et_pb_post_format();

        $classtext = 'on' === $fullwidth ? 'et_pb_post_main_image' : '';

        if ( in_array( $post_format, array( 'video', 'gallery' ) ) ) {
          $no_thumb_class = '';
        } ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' . $no_thumb_class . $overlay_class ); ?>>
          <div class="et_post_meta_wrapper">

						<?php
							if ( ! post_password_required() ) :

								$thumb = '';

								$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

								$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
								$classtext = 'et_featured_image';
								$titletext = get_the_title();
								$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
								$thumb = $thumbnail["thumb"];

                $use_divi_thumb = false;

								$post_format = et_pb_post_format();

								if ( 'video' === $post_format && false !== ( $first_video = et_get_first_video() ) ) {
									printf(
										'<div class="et_main_video_container">
											%1$s
										</div>',
										$first_video
									);
								} else if ( ! in_array( $post_format, array( 'gallery', 'link', 'quote' ) ) && false !== $use_divi_thumb && 'on' === et_get_option( 'divi_thumbnails', 'on' ) && '' !== $thumb ) {
									print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
								} else if ( 'gallery' === $post_format ) {
									et_pb_gallery_images();
								}
							?>

							<?php
								$text_color_class = et_divi_get_post_text_color();

								$inline_style = et_divi_get_post_bg_inline_style();

								switch ( $post_format ) {
									case 'audio' :
										printf(
											'<div class="et_audio_content%1$s"%2$s>
												%3$s
											</div>',
											esc_attr( $text_color_class ),
											$inline_style,
											et_pb_get_audio_player()
										);

										break;
									case 'quote' :
										printf(
											'<div class="et_quote_content%2$s"%3$s>
												%1$s
											</div> <!-- .et_quote_content -->',
											et_get_blockquote_in_content(),
											esc_attr( $text_color_class ),
											$inline_style
										);

										break;
									case 'link' :
										printf(
											'<div class="et_link_content%3$s"%4$s>
												<a href="%1$s" class="et_link_main_url">%2$s</a>
											</div> <!-- .et_link_content -->',
											esc_url( et_get_link_url() ),
											esc_html( et_get_link_url() ),
											esc_attr( $text_color_class ),
											$inline_style
										);

										break;
								}

							endif;
						?>
					</div> <!-- .et_post_meta_wrapper -->

  				<div class="">
  				<?php
  					do_action( 'et_before_content' );

  					the_content();

  					wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
  				?>
  				</div> <!-- .entry-content -->

          <div class="et_post_meta_wrapper">
  				<?php
  					if ( ( comments_open() || get_comments_number() ) && 'on' == et_get_option( 'divi_show_postcomments', 'on' ) && ! $et_pb_has_comments_module ) {
  						comments_template( '', true );
  					}
  				?>
  				</div> <!-- .et_post_meta_wrapper -->
  			</article> <!-- .et_pb_post -->

      <?php
      }
      wp_reset_query();
    }

    $posts = ob_get_contents();

    ob_end_clean();

    $class = " et_pb_module et_pb_bg_layout_{$background_layout}";

    $output = sprintf(
      '<div%5$s class="%1$s%3$s%6$s">
      %2$s
      %4$s',
      'et_pb_posts',
      $posts,
      esc_attr( $class ),
      ( ! $container_is_closed ? '</div> <!-- .et_pb_posts -->' : '' ),
      ( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
      ( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
    );

    return $output;
  }
}
new CAC_Custom_Module_Blog_Content;
