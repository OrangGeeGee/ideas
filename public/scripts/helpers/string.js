

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

/**
 *  Replaces variables between curly braces with the specified values.
 *
 *  Example:
 *    'Hello {foo}!'.supplementWith({ foo: 'world' })
 *    --> 'Hello world!'
 *
 *  Supports nested objects as well:
 *
 *    'Hello, Mr. {user.name}!'.supplementWith({ user: { name: 'Smith' } });
 *    --> 'Hello, Mr. Smith!'
 *
 *  @param {Object} data
 *    Will be used as the source where to look for variable values.
 *    The method can be supplied with more than one data object, in
 *    which case they'll be merged into one.
 *
 *  @return {String}
 */
String.prototype.supplementWith = function(data) {
  return this.replace(/\{([\w\.]*)\}/g, function(match, variable) {
    var keys = variable.split('.');
    var value = data[keys.shift()];

    keys.forEach(function(key) {
      value = value[key];
    });

    return /*( typeof value !== 'undefined' && value !== null ) ? */value || '';
  });
};


/**
 * @param {RegExp|String} pattern
 * @return {String}
 */
String.prototype.remove = function(pattern) {
  return this.replace(pattern, '');
};
