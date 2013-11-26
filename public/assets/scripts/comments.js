
var Comments = new Collection('comments');


var CommentListView = Backbone.View.extend({
  tagName: 'ul',
  id: 'comment-list',

  renderComment: function(comment) {
    var view = new CommentView({ model: comment });
    view.render().appendTo(this.$el);
  },

  initialize: function() {
    $('#right-side').empty().append(this.$el).show();

    Comments
      .where({ idea_id: this.model.id })
      .forEach(this.renderComment, this);
  }
});


var CommentView = Backbone.View.extend({
  tagName: 'li',
  template: _.template($('#comment-template').html()),

  render: function() {
    return this.$el.html(this.template(this.model.toJSON()));
  }
});
