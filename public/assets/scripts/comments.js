
var Comments = new Collection('comments');



var CommentFormView = Backbone.View.extend({
  tagName: 'form',
  template: _.template($('#comment-form-template').html()),

  events: {
    submit: 'submit',
    'keyup :input': 'toggleSubmitButton'
  },

  toggleSubmitButton: function() {
    var data = this.$el.parseAsJSON();
    this.$(':submit').prop('disabled', !data.text);
  },

  submit: function(event) {
    event.preventDefault();
    var data = this.$el.parseAsJSON();
    data.idea_id = this.model.id;

    Comments.create(data, { wait: true });
    this.el.reset();
    this.toggleSubmitButton();
  },

  render: function() {
    this.$el.html(this.template());
    this.toggleSubmitButton();

    return this.$el;
  }
});



var CommentListView = Backbone.View.extend({
  tagName: 'ul',
  id: 'comment-list',
  className: 'entry-list',

  renderComment: function(comment) {
    var view = new CommentView({ model: comment });
    view.render().insertAfter(this.$form.parent());
  },

  initialize: function() {
    var modal = Modals.open('comments').empty();

    modal.setTitle(this.model.get('title'));
    modal.setContent(this.$el);
    modal.resize();

    this.$form = new CommentFormView({ model: this.model }).render();
    this.$form.wrap('<li>').parent().appendTo(this.$el);

    Comments
      .where({ idea_id: this.model.id })
      .forEach(this.renderComment, this);

    this.listenTo(Comments, 'add', function(comment) {
      if ( comment.get('idea_id') === this.model.id ) {
        this.renderComment(comment);
      }
    }, this);
  }
});



var CommentView = Backbone.View.extend({
  tagName: 'li',
  template: _.template($('#comment-template').html()),

  render: function() {
    var randomUserId = Math.ceil(Math.random() * 4);

    this.$el.html(this.template(this.model.toJSON()))
    this.$('img').attr('src', 'assets/images/' + randomUserId + '.jpg');

    return this.$el;
  }
});
