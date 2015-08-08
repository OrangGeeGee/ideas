
var Layout = {};
Layout.$window = $(window);


/**
 * @returns {{ width: Number, height: Number }}
 */
Layout.getViewportDimensions = function() {
  return {
    width: Layout.$window.width(),
    height: Layout.$window.height()
  }
};


/**
 * @param {Function} callback
 * @param {Object} [context]
 */
Layout.onResize = function(callback, context) {
  Layout.$window.on('resize', function() {
    callback.call(context || this, Layout.getViewportDimensions());
  });
};


Layout.sync = function() {
  var viewport = Layout.getViewportDimensions();

  Layout.$header.outerWidth(viewport.width - Layout.$activitySection.width());
};


Layout.initialize = function() {
  Layout.$header = $('header');
  Layout.$activitySection = $('#activitySection');

  Layout.onResize(Layout.sync);
  Layout.sync();
};
