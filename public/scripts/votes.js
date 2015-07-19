
var Votes = new (Backbone.Collection.extend({
  url: 'votes',

  initialize: function() {
    this.on('add', function(vote) {
      Ideas.get(vote.get('idea_id')).votes.add(vote);
    });
  }
}));

Votes.model = Backbone.Model.extend({
  url: function() {
    return 'ideas/' + this.get('idea_id') + '/vote';
  }
});
