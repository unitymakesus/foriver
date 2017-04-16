'use strict';

function debounce(func, wait, immediate) {

  // Debounce
  // http://davidwalsh.name/javascript-debounce-function

  var timeout;
  return function() {
    var context = this,
      args = arguments;
    var later = function() {
      timeout = null;
      if (!immediate) {
        func.apply(context, args);
      }
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) {
      func.apply(context, args);
    }
  };
}

var htmlTag = document.getElementsByTagName('html')[0];
var videoContainer = document.querySelector('#video-background .et_pb_section_video_bg');
var videoElem = document.querySelector('#video-background video');

console.log(videoContainer);

var minW = 320; // Minimum video width allowed
var vidWOrig; // Original video dimensions
var vidHOrig;

vidWOrig = videoElem.getAttribute('width');
vidHOrig = videoElem.getAttribute('height');

var videoCover = function() {

  var winWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
  var winHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

  // Set the video viewport to the window size

  videoContainer.style.width = winWidth + 'px';
  videoContainer.style.height = winHeight + 'px';

  console.log(winHeight);

  // Use largest scale factor of horizontal/vertical
  var scaleH = winWidth / vidWOrig;
  var scaleV = winHeight / vidHOrig;
  var scale = scaleH > scaleV ? scaleH : scaleV;

  // Don't allow scaled width < minimum video width
  if (scale * vidWOrig < minW) {
    scale = minW / vidWOrig;
  }

  // Scale the video
  var videoNewWidth = scale * vidWOrig;
  var videoNewHeight = scale * vidHOrig;

  videoElem.style.width = videoNewWidth + 'px';
  videoElem.style.height = videoNewHeight + 'px';

  // Center it by scrolling the video viewport
  videoContainer.scrollLeft = (videoNewWidth - winWidth) / 2;
  videoContainer.scrollTop = (videoNewHeight - winHeight) / 2;

};

if (htmlTag.classList.contains('js')) {
  videoCover();

  // Adjust on resize
  var updateVideo = debounce(function() {
    videoCover();
  }, 100);

  window.addEventListener('resize', updateVideo);
}
