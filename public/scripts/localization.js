
var labels = {};


/**
 * @param {String} label
 * @param {String|Object} [translation]
 * @return {String}
 */
function localize(label, translation) {

  if ( typeof translation === 'string' ) {
    labels[label] = translation;
  }

  // The second argument is a data object for inserting values into the localized text.
  else if ( typeof translation === 'object' ) {
    return labels[label].supplementWith(translation);
  }

  else {
    return labels[label];
  }
}
