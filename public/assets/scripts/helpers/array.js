

/**
 * @return {*}
 */
Array.prototype.first = function() {
  return this[0];
};


/**
 * @param {*} item
 * @return {Array}
 */
Array.prototype.without = function(item) {
  return this.filter(function() {
    return arguments[0] !== item;
  });
};


/**
 * @param {String} method
 */
Array.prototype.invoke = function(method) {
  this.forEach(function(item) {
    if ( item[method] ) {
      item[method]();
    }
  });
};
