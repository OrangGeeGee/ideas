
var queries = [];

/**
 *
 * @param {String} url
 * @param {Function} [callback]
 * @constructor
 */
function Collection(url, callback) {
  var collection = $.extend(new Backbone.Collection, {
    url: url
  });

  queries.push(collection.fetch());

  if ( $.isFunction(callback) ) {
    $(document).on('initial-data-loaded', callback.bind(collection));
  }

  return collection;
}

$(function() {
  $.when.apply($, queries).done(function() {
    $(document).trigger('initial-data-loaded');

    setInterval(function() {
      $.getJSON('update', function(response) {
        $.each(response, function(module, data) {
          if ( data.length ) {
            window[module].add(data, { merge: true });
          }
        });
      });
    }, 5000);
  });
});