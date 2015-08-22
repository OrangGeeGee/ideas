

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


/**
 * @param {String} dataType
 * @return {Boolean}
 */
String.prototype.isValid = function(dataType) {
  var pattern;

  switch ( dataType ) {
    case 'email':
      pattern = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
      break;

    default:
      throw new TypeError(dataType + ' is not a supported validation type.');
  }

  return pattern.test(this);
};
