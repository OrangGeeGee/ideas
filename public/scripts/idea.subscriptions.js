
var Subscriptions = new (Backbone.Collection.extend({

  initialize: function() {
    this.on('add', function(subscription) {
      var idea = Ideas.get(subscription.get('idea_id'));

      if ( idea ) {
        idea.subscriptions.add(subscription);
      }
    });
  },


  /**
   * @param {Backbone.Model} idea
   * @return {Backbone.Model}
   */
  getByIdea: function(idea) {
    return this.where({
      idea_id: idea.id,
      user_id: USER_ID
    })[0];
  }
}));

Subscriptions.model = Backbone.Model.extend({
  url: function() {
    return 'ideas/' + this.get('idea_id') + '/subscription';
  }
});
