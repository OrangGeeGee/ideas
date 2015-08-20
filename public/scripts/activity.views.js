


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



var ActivityListView = Backbone.View.extend({
  tagName: 'ul',
  className: 'activity-list',
  minimumDate: moment().subtract(1, 'week').format('YYYY-MM-DD'),

  renderIdea: function(idea) {
    var ideaView = new ActivityListIdeaView({ model: idea });
    this.$el.prepend(ideaView.$el);
  },

  renderComment: function(comment) {

    // Check if the idea has been deleted.
    if ( !Ideas.get(comment.get('idea_id')) ) {
      return;
    }

    var commentView = new ActivityListCommentView({ model: comment });
    this.$el.prepend(commentView.$el);
  },

  renderItem: function(item) {
    switch ( item.collection.url ) {
      case 'ideas':
        this.renderIdea(item);
        break;

      case 'comments':
        this.renderComment(item);
        break;
    }
  },

  resize: function() {
    var viewport = Layout.getViewportDimensions();
    var PADDING = parseInt(this.$el.css('padding-top'));

    this.$el.height(viewport.height - this.$el.position().top - PADDING * 2);
  },

  initialize: function() {
    Activities.each(this.renderItem, this);
    Activities.on('add', this.renderItem, this);

    this.resize();
    Layout.onResize(this.resize, this);
  }
});



var ActivityListCommentView = Backbone.View.extend({
  tagName: 'li',
  className: 'comment',
  template: _.template($('#activityListCommentTemplate').html()),

  render: function() {
    this.$el.html(this.template(this.model));
    this.$el.linkify({
      target: '_blank'
    });
    this.$('.entry-content').append(new TimestampView({ model: this.model }).$el);
  },

  initialize: function() {
    this.render();
  }
});



var ActivityListIdeaView = Backbone.View.extend({
  tagName: 'li',
  template: _.template($('#activityListIdeaTemplate').html()),

  render: function() {
    this.$el.html(this.template(this.model));
    this.$('.entry-content').append(new TimestampView({ model: this.model }).$el);
  },

  initialize: function() {
    this.render();
  }
});
