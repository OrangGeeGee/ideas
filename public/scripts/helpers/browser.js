
$(function() {
  var testElement = document.createElement('div');
  var fileInputElement = $('<input type="file"/>')[0];
  var $html = $('html');
  var features = {
    'files-property': 'files' in fileInputElement
  };

  $.each(features, function(feature, isSupported) {
    $html.addClass(isSupported ? feature + '-supported' : feature + '-unsupported');
  });
});
