
var $window = $(window);
var resizeTimer;

$window.on('resize', function() {
  clearTimeout(resizeTimer);

  resizeTimer = setTimeout(function() {
    $window.trigger('resize-finish');
  }, 200);
});
