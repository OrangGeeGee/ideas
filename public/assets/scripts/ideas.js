
var Ideas = new Collection('ideas', function() {
  new IdeaFormView().render().appendTo('body');
  var $ideasList = new IdeaListView({ collection: Ideas }).$el.appendTo('body');

  $ideasList.before('<h2>Viimased ideed</h2>');
});

Ideas.comparator = function(idea1, idea2) {
  return idea1.get('created_at') < idea2.get('created_at');
};



var IdeaFormView = Backbone.View.extend({
  tagName: 'form',
  template: _.template($('#idea-form-template').html()),

  events: {
    submit: 'submit',
    'keyup :input': 'toggleSubmitButton'
  },

  toggleSubmitButton: function() {
    var data = this.$el.parseAsJSON();
    this.$(':submit').prop('disabled', !data.title || !data.description);
  },

  submit: function(event) {
    event.preventDefault();
    var data = this.$el.parseAsJSON();

    Ideas.create(data, { wait: true });
    this.el.reset();
    this.toggleSubmitButton();
  },

  render: function() {
    this.$el.html(this.template());
    this.toggleSubmitButton();

    return this.$el;
  }
});



var IdeaView = Backbone.View.extend({
  tagName: 'li',
  template: _.template($('#idea-template').html()),

  events: {
    'click .entry-content': 'openIdea'
  },

  openIdea: function() {
    var commentList = new CommentListView({ model: this.model });
  },

  render: function() {
    var data = this.model.toJSON();
    data.comments = Comments.where({ idea_id: this.model.id });
    data.user = Users.get(data.user_id).toJSON();

    this.$el.html(this.template(data));

    if ( this.model.get('status_id') == 1 ) {
      this.$el.addClass('finished');
    }

    return this.$el;
  }
});



var IdeaListView = Backbone.View.extend({
  tagName: 'ul',
  id: 'ideas-list',
  className: 'entry-list',

  renderIdea: function(idea) {
    var view = new IdeaView({ model: idea });
    this.$el.append(view.render());
  },

  initialize: function() {
    this.collection.each(this.renderIdea, this);
    this.collection.on('add', this.renderIdea, this);
  }
});
