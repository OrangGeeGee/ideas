
var Votes = new (Backbone.Collection.extend({
  url: 'votes',

  initialize: function() {
    this.on('add', function(vote) {
      var idea = Ideas.get(vote.get('idea_id'));

      if ( idea ) {
        idea.votes.add(vote);
      }
    });
  }
}));

Votes.model = Backbone.Model.extend({
  url: function() {
    return 'ideas/' + this.get('idea_id') + '/vote';
  }
});
