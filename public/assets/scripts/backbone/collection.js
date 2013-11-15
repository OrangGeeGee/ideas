
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
  $(document).on('initial-data-loaded', callback);

  return collection;
}

$(function() {
  $.when.apply($, queries).done(function() {
    $(document).trigger('initial-data-loaded');
  });
});