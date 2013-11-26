
/**
 * @return {Object}
 */
$.fn.parseAsJSON = function() {
  var data = {};

  this.serializeArray().forEach(function(field) {
    data[field.name] = field.value;
  });

  return data;
};


/**
 *  Centers the current set both horizontally and vertically.
 *
 *  @author
 *    Mattias Saldre
 *
 *  @return {jQuery}
 *    Current set.
 */
$.fn.center = function () {
  var $window = $(window),
      viewportWidth = $window.width(),
      viewportHeight = $window.height();

  return this.each(function() {
    var $element = $(this),
        remainingVerticalSpace = viewportHeight - $element.outerHeight(),
        remainingHorizontalSpace = viewportWidth - $element.outerWidth();

    $element.css({
      'top': ( remainingVerticalSpace > 0 ) ? remainingVerticalSpace / 2 : 0,
      'left': ( remainingHorizontalSpace > 0 ) ? remainingHorizontalSpace / 2 : 0
    });
  });
};
