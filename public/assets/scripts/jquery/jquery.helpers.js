
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
