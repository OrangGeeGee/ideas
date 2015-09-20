

/**
 * @param {String} url
 * @return {String}
 */
function getYoutubeVideoId(url) {
  var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/m;
  var match = url.match(regExp);

  if  ( match && match[2].length == 11 ) {
    return match[2];
  }
}


/**
 * @param {String} videoId
 * @return {String}
 */
function generateEmbeddedYoutubeVideo(videoId) {
  return '<iframe class="youtube-video" src="//www.youtube.com/embed/' + videoId + '" allowfullscreen></iframe>';
}
