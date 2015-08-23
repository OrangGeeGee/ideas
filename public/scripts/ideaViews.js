
var IdeaViews = new (Backbone.Collection.extend({

  initialize: function() {
    this.on('add', function(view) {
      var idea = Ideas.get(view.get('idea_id'));

      if ( idea ) {
        idea.views.add(view);
      }
    })
  }
}));

IdeaViews.model = Backbone.Model.extend({
  url: function() {
    return 'ideas/' + this.get('idea_id') + '/views';
  }
});
