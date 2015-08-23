
var CommentLikes = new (Backbone.Collection.extend({

  initialize: function() {
    this.on('add', function(like) {
      var comment = Comments.get(like.get('comment_id'));
      comment.likes.add(like);
    });
  }
}));

CommentLikes.model = Backbone.Model.extend({
  url: function() {
    return 'comments/' + this.get('comment_id') + '/like';
  }
});