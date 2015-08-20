
var Comments = new (Backbone.Collection.extend({
  url: 'comments',

  initialize: function() {
    this.on('add', function(comment) {
      var idea = Ideas.get(comment.get('idea_id'));

      if ( idea ) {
        idea.comments.add(comment);
      }
    });
  }
}));
