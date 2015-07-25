


var CommentFormView = Backbone.View.extend({
  tagName: 'form',
  className: 'comment-form',
  template: _.template($('#commentFormTemplate').html()),

  events: {
    submit: 'submit',
    'keyup :input': 'toggleSubmitButton'
  },

  toggleSubmitButton: function() {
    var data = this.$el.parseAsJSON();
    this.$(':submit').prop('disabled', !data.text);
  },

  enableForm: function() {
    this.$el.removeClass('loading');
    this.$(':input').removeAttr('disabled');
  },

  disableForm: function() {
    this.$el.addClass('loading');
    this.$(':input').attr('disabled', 'disabled');
  },

  submit: function(event) {
    event.preventDefault();
    var data = this.$el.parseAsJSON();
    data.idea_id = this.model.id;

    this.disableForm();
    Comments.create(data, {
      wait: true,
      success: function() {
        this.enableForm()
        this.el.reset();
      }.bind(this)
    });
  },

  render: function() {
    this.$el.html(this.template());
    this.toggleSubmitButton();
  },

  initialize: function() {
    this.render();
  }
});



var CommentView = Backbone.View.extend({
  tagName: 'li',
  template: _.template($('#commentListItemTemplate').html()),

  render: function() {
    var data = this.model.toJSON();
    data.user = Users.get(data.user_id);

    this.$el.html(this.template(data));
    this.$('.entry-author').append(new TimestampView({ model: this.model }).$el);
  },

  initialize: function() {
    this.render();
  }
});