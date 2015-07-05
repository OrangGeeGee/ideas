
$(function() {
  var testElement = document.createElement('div');
  var $body = $('body');
  var features = {
    'box-shadow': 'boxShadow' in testElement.style
  };

  $.each(features, function(feature, isSupported) {
    $body.toggleClass(feature, isSupported);
  });
});
