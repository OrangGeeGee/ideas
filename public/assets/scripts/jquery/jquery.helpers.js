
/**
 * @return {Object}
 */
$.fn.parseAsJSON = function() {
  var $form = this;
  var data = {};

  this.find(':input[name]').each(function() {
    var $field = $(this);

    if ( $field.is(':radio') ) {
      if ( !(this.name in data) ) {
        data[this.name] = $form.field(this.name).filter(':checked').val();
      }
    }
    else {
      data[this.name] = $field.is(':checkbox') ? this.checked === true : this.value;
    }
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


/**
 * @param {String} fieldName
 * @return {jQuery}
 */
$.fn.field = function(fieldName) {
  return this.find(':input[name="' + fieldName + '"]');
};


/**
 * Mimics placeholder support for older browsers.
 *
 * @return {jQuery}
 */
$.fn.mimicPlaceholder = function() {
  return this.each(function() {
    var $field = $(this);
    var placeholderText = $field.attr('placeholder');

    $field.on({
      focus: function() {
        if ( $field.val() == placeholderText ) {
          $field.val('').removeClass('placeholder');
        }
      },
      blur: function() {
        if ( !$field.val() ) {
          $field.val(placeholderText).addClass('placeholder');
        }
      }
    });

    // Make sure the field starts with the placeholder text.
    $field.trigger('blur');
  });
};
