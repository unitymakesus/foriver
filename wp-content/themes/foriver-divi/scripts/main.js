// Monotype font tracking code
// var MTUserId='b5cf68b4-ebc2-4673-83c0-e79e9546f0f4';
// var MTFontIds = new Array();
//
// MTFontIds.push("1475500"); // Avenir® W04 35 Light
// MTFontIds.push("1475536"); // Avenir® W04 65 Medium
// MTFontIds.push("1475548"); // Avenir® W04 85 Heavy
// MTFontIds.push("1603832"); // Futura® BT W04 Condensed Light
// MTFontIds.push("1603848"); // Futura® BT W04 Light
// (function() {
//     var mtTracking = document.createElement('script');
//     mtTracking.type='text/javascript';
//     mtTracking.async='true';
//     mtTracking.src='mtiFontTrackingCode.js';
//
//     (document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]).appendChild(mtTracking);
// })();



jQuery(document).ready(function($) {
  /**
   * Background video auto-play
   * Make sure video plays on load, since we dequeued the waypoints script in
   * Divi that was causing the video to pause when it is out of the viewport.
   *
   */

  var $video = $(".home #video-background video");

  if ($video !== null) {
    $video.mediaelementplayer( {
  		success : function( mediaElement, domObject ) {
  			mediaElement.addEventListener( 'loadeddata', function() {
  				et_pb_resize_section_video_bg( $(domObject) );
  				et_pb_center_video( $(domObject) );
  			}, false );

  			mediaElement.addEventListener( 'canplay', function() {
  				$(domObject).closest( '.et_pb_preload' ).removeClass( 'et_pb_preload' );
          $video.get(0).play(); // Play video
          $('.fade-in-text').addClass('show');  // Fade in home page title text
  			}, false );
  		}
  	} );
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
