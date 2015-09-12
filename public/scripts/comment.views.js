


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
      success: function(comment) {
        this.enableForm();
        this.$('[name="text"]').val('');
        this.toggleSubmitButton();

        // Generate status change manually before the real data
        // from the server comes through.
        var idea = Ideas.get(data.idea_id);

        if ( data.status_id && data.status_id != idea.getStatusId() ) {
          StatusChanges.add({
            comment_id: comment.id,
            idea_id: data.idea_id,
            status_id: data.status_id
          });

          // HACK:
          // Force the comment to re-render so that the status
          // change will be visible.
          comment.trigger('change');
        }
      }.bind(this)
    });
  },

  addMentioning: function() {
    var userNames = Users.pluck('name');

    userNames.forEach(function(name, index) {
      userNames[index] = name.remove(/ /g);
    });

    this.$('textarea').atwho({
      at: '@',
      data: userNames
    });
  },

  render: function() {
    this.$el.html(this.template(this.model));
    this.toggleSubmitButton();
    this.addMentioning();

    this.$('[name="status_id"]').val(this.model.getStatusId());
  },

  initialize: function() {
    this.render();
  }
});



var CommentView = Backbone.View.extend({
  tagName: 'li',
  template: _.template($('#commentListItemTemplate').html()),

  events: {
    'click .comment-vote-action': function(event) {
      event.preventDefault();
      this.model.like();
    }
  },

  embedYoutubeLinks: function() {
    var videoId = getYoutubeVideoId(this.model.get('text'));

    if ( videoId ) {
      this.$('.entry-content').append(generateEmbeddedYoutubeVideo(videoId));
    }
  },

  render: function() {
    var data = this.model.toJSON();
    data.user = Users.get(data.user_id);
    data.statusChange = Ideas.get(data.idea_id).statusChanges.where({ comment_id: data.id })[0];
    data.likes = this.model.likes;
    data.isLiked = this.model.isLiked();

    this.$el.html(this.template(data));
    this.$el.linkify({
      target: '_blank'
    });
    this.$('.comment-footer').append(new TimestampView({ model: this.model }).$el);
    this.embedYoutubeLinks();
  },

  initialize: function() {
    this.render();
    this.model.on('change', this.render, this);
    this.model.likes.on('add', this.render, this);
  }
});