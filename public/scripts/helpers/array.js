

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

if ( !Array.prototype.forEach ) {
  Array.prototype.forEach = function(callback, context) {
    var array = this;

    for ( var index = 0; index < array.length; index++ ) {
      var element = array[index];
      callback.call(context || this, element, index, array);
    }
  };
}

if ( !Array.prototype.filter ) {
  Array.prototype.filter = function(callback) {
    var filteredSet = [];

    this.forEach(function(element) {
      if ( callback.apply(this, arguments) ) {
        filteredSet.push(element);
      }
    });

    return filteredSet;
  };
}
