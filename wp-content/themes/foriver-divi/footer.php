<?php if ( 'on' == et_get_option( 'divi_back_to_top', 'false' ) ) : ?>

	<span class="et_pb_scroll_top et-pb-icon"></span>

<?php endif; ?>

<?php if ( ! is_page_template( 'page-template-blank.php' ) ) : ?>

  	<footer id="main-footer" class="et_pb_gutters2">
      <?php echo do_shortcode('[et_pb_section global_module="171"]'); // Global footer layout ?>
  	</footer>
  </div> <!-- #et-main-area -->

<?php endif; // ! is_page_template( 'page-template-blank.php' ) ?>

</div> <!-- #page-container -->

<?php wp_footer(); ?>
</body>
</html>
