
function hideLandingPage() {
  $('#landingLayer').remove();
  $.post('users/settings', { landingPageVisited: 1 });
}
