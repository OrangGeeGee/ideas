
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

Comments.model = Backbone.Model.extend({
  url: 'comments',

  like: function() {
    CommentLikes.create({
      comment_id: this.id
    }, {
      wait: true
    })
  },

  isLiked: function() {
    return this.likes.where({ user_id: USER_ID }).length > 0;
  },

  initialize: function() {
    this.likes = new Backbone.Collection;
  }
});
