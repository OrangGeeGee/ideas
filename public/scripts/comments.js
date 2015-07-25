
var Comments = new (Backbone.Collection.extend({
  url: 'comments',

  initialize: function() {
    this.on('add', function(comment) {
      Ideas.get(comment.get('idea_id')).comments.add(comment);
    });
  }
}));
