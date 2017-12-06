jQuery(document).ready(function($) {
  /**
   * Background video auto-play
   * Make sure video plays on load, since we dequeued the waypoints script in
   * Divi that was causing the video to pause when it is out of the viewport.
   *
   */

  var $video = $(".home #video-background video");


  if ($video.length) {
    $video.on('play', function() {
      $('.fade-in-text').addClass('show');  // Fade in home page title text
    });
  }


  /**
   * Custom JS for donation form
   *
   */

  // Make monthly active by default
  $("#form_page_1_pg_1 input[type='radio'][value='monthly']").closest('label').addClass('active');

  // Add active class to radio wrapper for selected elements
  $("#form_page_1_pg_1").on("click", "input[type='radio']", function() {
    if ($(this).is(':checked')) {
      // Add active class
      $(this).closest('.radio, .radio-inline').addClass('active').siblings().removeClass('active');
    }
  });
});
