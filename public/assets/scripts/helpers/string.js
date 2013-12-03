

/**
 * @return {String}
 */
String.prototype.getForename = function() {
  return this.split(' ').first();
};
