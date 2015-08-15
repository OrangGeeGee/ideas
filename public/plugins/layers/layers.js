

/**
 * Creates a new layer near the options.$target
 *
 * @param {Object} options
 * @constructor
 */
function Layer(options) {
  $.extend(this, {
    id: '',
    $target: $(),
    content: ''
  }, options);

  if ( $('#' + this.id).is(':visible') ) {
    return;
  }

  var template = $('#layerTemplate').html();
  this.$ = $(template).attr({
    'id': this.id,
    'class': this.className
  }).prependTo('body');

  if ( this.content instanceof Backbone.View ) {
    this.$.find('.layer-body').append(this.content.$el);
  }
  else {
    this.$.find('.layer-body').html(this.content);
  }

  this.position();
  this.closeOnBackgroundInteraction();

  Layout.onResize(this.position, this);
}


/**
 * Closes the layer when user clicks on background or hits Escape key.
 */
Layer.prototype.closeOnBackgroundInteraction = function() {
  var layer = this;
  var ESCAPE_KEY = 27;

  $('body').on('mousedown', function(event) {
    if ( !layer.$.find(event.target).length && !layer.$.is(event.target) ) {
      layer.remove();
    }
  });

  $(document).on('keydown', function(event) {
    if ( event.which == ESCAPE_KEY ) {
      layer.remove();
    }
  });
};


/**
 * Positions the tooltip above the $target.
 */
Layer.prototype.position = function() {
  var layerWidth = this.$.width();
  var targetPosition = {
    left: this.$target.offset().left,
    top: this.$target.position().top
  };
  var targetHeight = this.$target.outerHeight();
  var targetWidth = this.$target.outerWidth();
  var tooltipPointerWidth = 20;
  var tooltipPointerHeight = 20;

  this.$.css({
    top: targetPosition.top + targetHeight + tooltipPointerHeight,
    left: targetPosition.left + (targetWidth / 2) - layerWidth + tooltipPointerWidth
  });
};


/**
 * Destroys the tooltip from the DOM.
 */
Layer.prototype.remove = function() {
  this.$.remove();
};
