
function hideLandingPage() {
  $('#landingLayer').remove();
  $.get('landingPageVisited');
}
