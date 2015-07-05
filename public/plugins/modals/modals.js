
var Modals = {};
var $window = $(window);

// Cache all modal windows as jQuery objects.
// Keys will represent modal's ID.
Modals.cache = {};

Modals.$mask = null;
Modals.active = null;


/**
 * @constructor
 * @private
 * @param {String} id
 * @param {String|jQuery|Backbone.View} content
 * @return {Modal}
 */
function Modal(id, content) {
  var modal = Modals.cache[id] = this;
  this.id = id;
  this.$ = $('#' + id);

  // The element wasn't found, create an empty modal.
  if ( !this.$.length ) {
    this.$ = createBlankModal(id);
  }

  this.$body = this.$.find('.modal-body');
  this.$title = this.$body.children('h2:first');
  this.setContent(content).open();

  this.$.on('click', '.modal-close-action', function(event) {
    event.preventDefault();
    modal.close();
  });

  return this;
}


/**
 *  Creates a blank modal window with no content in the DOM.
 *
 *  @private
 *  @param {String} id
 *  @return {jQuery}
 */
function createBlankModal(id) {
  var $modal = $('<div/>', {
    'class': 'modal-window',
    'id': id
  });

  $modal.append(
    '<a href="#" class="modal-close-action"/>' +
    '<div class="modal-body">' +
    '  <h2></h2>' +
    '</div>'
  );

  return $modal.insertAfter(Modals.$mask);
}


/**
 */
Modal.prototype.open = function() {
  var modal = Modals.active = this;

  $('body').addClass('modal-window-open')
  this.$.show().center();

  if ( !this.$.data('original-height') ) {
    this.$.data('original-height', this.$.height());
  }

  $(document).one('keyboard:escape', function() {
    modal.close();
  });

  return this;
};


/**
 */
Modal.prototype.close = function() {
  Modals.active = null;

  $('body').removeClass('modal-window-open');
  this.$.hide();

  // Allow callbacks to be queued to the event.
  this.$.trigger('modal-close', [this]);

  // Remove opened idea's ID from the URL. Avoid scrolling the page back to the top.
  var scrollPosition = $('html').scrollTop() || $('body').scrollTop();
  location.href = '#';
  $('html, body').scrollTop(scrollPosition);

  return this;
};


/**
 * @param {Number|String} title
 * @return {Modal}
 */
Modal.prototype.setTitle = function(title) {
  this.$title.html(title);
  return this;
};


/**
 * @param {String|jQuery|Backbone.View} content
 * @return {Modal}
 */
Modal.prototype.setContent = function(content) {
  if ( content instanceof Backbone.View ) {
    content = content.render();
  }

  if ( this.$title.length ) {
    this.$body.html(this.$title);
  } else {
    this.$body.empty();
  }

  this.$body.append(content);
  this.$.data('original-height', this.$.height());

  return this;
};


/**
 * Calculates available height for the modal, based on the viewport dimensions.
 *
 * @return {Number}
 */
Modal.prototype.getAvailableHeight = function() {
  var viewportHeight = $window.height(),
      modalHeight = viewportHeight * 0.92,
      maxHeight = parseInt(this.$body.css('max-height'));

  if ( modalHeight > parseInt(this.$body.css('max-height')) ) {
    modalHeight = maxHeight;
  }

  return modalHeight;
};


/**
 * Syncs modal height and position according to the viewport.
 */
Modal.prototype.resize = function() {
  var bodyMargin = parseInt(this.$body.css('margin-top'));
  var bodyPadding = parseInt(this.$body.css('padding-bottom'));

  this.$body.height(this.getAvailableHeight() - bodyMargin - bodyPadding);
  this.$.center();
};


/**
 * Empties the modal content.
 *
 * @return {Modal}
 */
Modal.prototype.empty = function() {
  return this.setTitle('').setContent('');
};


/**
 * @param {String} id
 * @return {Modal}
 */
Modals.create = function(id) {
  return new Modal(id);
};


/**
 * @param {String} id
 * @return {Modal}
 */
Modals.get = function(id) {
  return Modals.cache[id];
};


/**
 * @param {String} id
 */
Modals.open = function(id) {
  var modal = Modals.get(id) || Modals.create(id);

  return modal.open();
};


/**
 * @param {String} id
 */
Modals.close = function(id) {
  Modals.get(id).close();
};


/**
 * Creates a confirm window with "Yes"/"No" buttons. Callback for
 * "Yes" can be specified via confirm().done() method like so:
 *
 * Modals.confirm('Are you sure?').done(function() {
 *   // "Yes" button was clicked.
 * });
 *
 * @param {String} title
 * @return {$.Deferred}
 */
Modals.confirm = function(title) {
  var modal = Modals.create('confirm-modal'),
      deferred = new $.Deferred;

  modal.setTitle(title).setContent(
    '<a href="#" class="button negative">Ei</a>' +
    '<a href="#" class="button">Jah</a>'
  ).open();

  modal.$.find('.button').on('click', function(event) {
    event.preventDefault();
    var $action = $(this);

    if ( !$action.hasClass('negative') ) {
      deferred.resolve();
    }

    modal.close();
  });

  return deferred.promise();
};


/**
 * Stylized alert window that can have a callback assigned for
 * when the customer clicks "Ok". Callback assigning:
 *
 * Modals.alert('Lorem ipsum dolor sit amet.').done(function() {
 *   // "Ok" button was clicked.
 * });
 *
 * @param {String} text
 * @return {$.Deferred}
 */
Modals.alert = function(text) {
  return $.Deferred(function(deferred) {
    var modal = Modals.create('alert-modal');

    modal.setTitle(text).setContent(
      '<a href="#" class="button">Ok!</a>'
    ).open();

    modal.$.find('.button').on('click', function() {
      deferred.resolve();
      modal.close();
    });
  });
};


$(function() {
  Modals.$mask = $('<div id="modal-mask"/>').prependTo('body');

  Modals.$mask.on('click', function() {
    Modals.active.close();
  });

  // Keep modal windows centered with browser resizes.
  $window.on('resize-finish', function() {
    if ( Modals.active ) {
      Modals.active.resize();
    }
  });

  // Close the active modal via ESC key.
  $(document).on('keydown', function(event) {
    if ( event.which == 27 && Modals.active ) {
      Modals.active.close();
    }
  })
});
