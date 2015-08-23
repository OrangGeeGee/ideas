
var CommentLikes = new (Backbone.Collection.extend({
  url: 'comments/likes',

  initialize: function() {
    this.on('add', function(like) {
      console.log('add', like);
      var comment = Comments.get(like.get('comment_id'));
      comment.likes.add(like);
    });
  }
}));
