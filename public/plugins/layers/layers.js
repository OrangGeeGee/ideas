

/**
 * Creates a new layer near the options.$target
 *
 * @param {Object} options
 * @constructor
 */
function Layer(options) {
  $.extend(this, {
    $target: $(),
    content: ''
  }, options);

  var template = $('#layerTemplate').html();
  this.$ = $(template).addClass(this.className).prependTo('body');

  if ( this.content instanceof Backbone.View ) {
    this.$.find('.layer-body').append(this.content.$el);
  }
  else {
    this.$.find('.layer-body').html(this.content);
  }

  this.position();
}


/**
 * Positions the tooltip above the $target.
 */
Layer.prototype.position = function() {
  var targetPosition = this.$target.offset();
  var targetHeight = this.$target.outerHeight();
  var targetWidth = this.$target.outerWidth();
  var tooltipPointerWidth = 20;
  var tooltipPointerHeight = 20;

  this.$.css({
    top: targetPosition.top + targetHeight + tooltipPointerHeight,
    left: targetPosition.left + (targetWidth / 2) - tooltipPointerWidth
  });
};


/**
 * Destroys the tooltip from the DOM.
 */
Layer.prototype.remove = function() {
  this.$.remove();
};
