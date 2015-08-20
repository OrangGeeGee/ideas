
var labels = {};


/**
 * @param {String} label
 * @param {String} [translation]
 * @return {String}
 */
function localize(label, translation) {

  if ( arguments.length == 1 ) {
    return labels[label];
  }

  labels[label] = translation;
}
