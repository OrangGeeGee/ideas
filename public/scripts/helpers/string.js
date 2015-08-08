

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


/**
 * @param {String|String[]} snippet
 * @return {Boolean}
 */
String.prototype.endsWith = function(snippet) {

  if ( $.isArray(snippet) ) {
    var string = String(this);

    return snippet.filter(function(keyword) {
      return string.endsWith(keyword);
    }).length > 0;
  }

  return this.substr(-snippet.length) == snippet;
};
