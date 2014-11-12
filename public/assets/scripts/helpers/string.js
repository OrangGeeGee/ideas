

/**
 * @return {String}
 */
String.prototype.getForename = function() {
  return this.split(' ').first();
};


/**
 * @param {String} str
 * @return {Boolean}
 */
String.prototype.contains = function(str) {
  return this.indexOf(str) !== -1;
};
