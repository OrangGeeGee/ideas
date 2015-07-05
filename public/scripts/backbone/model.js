

/**
 * @param {String} key
 * @return {Backbone.Model}
 */
Backbone.Model.prototype.decrement = function(key) {
  return this.set(key, this.get(key) - 1);
};


/**
 * Returns all other models in the collection beside the current one.
 *
 * @return {Backbone.Model[]}
 */
Backbone.Model.prototype.others = function() {
  return this.collection.models.without(this);
};
