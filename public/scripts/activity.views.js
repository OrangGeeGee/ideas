
var IdeaActivityListView = Backbone.View.extend({
  tagName: 'ul',
  className: 'activity-list',

  renderComment: function(comment) {
    var commentView = new CommentView({ model: comment });
    this.$el.prepend(commentView.$el);
  },

  renderEvent: function(event) {
    var eventView = new EventView({ model: event });
    this.$el.prepend(eventView.$el);
  },

  render: function() {
    this.model.comments.each(this.renderComment, this);
    this.model.events.each(this.renderEvent, this);
  },

  initialize: function() {
    this.render();
    this.model.comments.on('add', this.renderComment, this);
  }
});
