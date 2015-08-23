


var EventFormView = Backbone.View.extend({
  tagName: 'form',
  template: _.template($('#eventFormTemplate').html()),
  callbacks: [],

  events: {
    submit: 'submit',
    'keyup :input': 'toggleSubmitButton'
  },

  toggleSubmitButton: function() {
    var data = this.$el.parseAsJSON();
    this.$(':submit').prop('disabled', !data.title || !data.description);
  },

  afterSubmit: function(callback) {
    this.callbacks.push(callback);
  },

  submit: function(event) {
    event.preventDefault();
    var data = this.$el.parseAsJSON();

    this.$el.addClass('loading');
    this.$(':input').attr('disabled', 'disabled');
    Ideas.create(data, {
      wait: true,
      success: function() {
        this.callbacks.forEach(function(callback) {
          callback();
        });
      }.bind(this)
    });
  },

  render: function() {
    this.$el.html(this.template());
    this.$el.field('category_id').val($('#category').val());
    this.toggleSubmitButton();

    if ( !('placeholder' in document.createElement('input')) ) {
      this.$('[placeholder]').mimicPlaceholder();
    }

    return this.$el;
  }
});



var IdeaFormView = Backbone.View.extend({
  tagName: 'form',
  template: _.template($('#ideaFormTemplate').html()),
  callbacks: [],

  events: {
    submit: 'submit',
    'change select[name="category_id"]': 'changeCategory',
    'keyup :input': 'toggleSubmitButton'
  },

  changeCategory: function() {
    $('#category').val(this.$('select[name="category_id"]').val()).trigger('change');
  },

  toggleSubmitButton: function() {
    var data = this.$el.parseAsJSON();
    this.$(':submit').prop('disabled', !data.title || !data.description);
  },

  afterSubmit: function(callback) {
    this.callbacks.push(callback);
  },

  submit: function(event) {
    event.preventDefault();
    var data = this.$el.parseAsJSON();

    this.$el.addClass('loading');
    this.$(':input').attr('disabled', 'disabled');
    Ideas.create(data, {
      wait: true,
      success: function() {
        this.callbacks.forEach(function(callback) {
          callback();
        });
      }.bind(this)
    });
  },

  render: function() {
    this.$el.html(this.template());
    this.$el.field('category_id').val($('#category').val());
    this.toggleSubmitButton();

    if ( !('placeholder' in document.createElement('input')) ) {
      this.$('[placeholder]').mimicPlaceholder();
    }

    return this.$el;
  }
});


var IdeaModalView = Backbone.View.extend({
  template: _.template($('#ideaModalTemplate').html()),

  events: {
    'click .edit-action': function(event) {
      event.preventDefault();
      this.$('.idea-form').addClass('editing');
    },
    'submit .idea-form': function(event) {
      event.preventDefault();
      var data = $(event.target).parseAsJSON();

      this.model.save(data);
      this.$('.idea-form').removeClass('editing');
    },
    'click .vote-action': function(event) {
      event.preventDefault();

      if ( $(event.currentTarget).is('.waiting') ) {
        return;
      }

      // TODO: Duplicated code from IdeaView.
      if ( this.model.hasBeenVotedFor() ) {
        this.model.removeVote();
      }
      else {
        var $button = $(event.currentTarget).addClass('waiting');

        this.model.vote(function() {
          $button.removeClass('waiting');
        });
      }
    }
  },

  render: function() {
    this.$el.html(this.template(this.model));
    this.$el.linkify({
      target: '_blank'
    });
  },

  refreshState: function() {
    this.modal.$.toggleClass('voted', this.model.hasBeenVotedFor());
  },

  initialize: function() {
    this.render();

    var commentFormView = new CommentFormView({ model: this.model });
    this.$('.idea-activity-section').append(commentFormView.$el);

    var activityListView = new IdeaActivityListView({ model: this.model });
    this.$('.idea-activity-section').append(activityListView.$el);

    this.modal = new Modal('ideaModal', this.$el);

    this.refreshState();
    this.model.votes.on('add remove', function() {
      this.refreshState();
    }, this);

    this.model.on('change', function() {
      this.$('.idea-title').text(this.model.get('title'));
      this.$('.idea-description').text(this.model.get('description'));
    }, this);
  }
});



var IdeaView = Backbone.View.extend({
  tagName: 'li',
  attributes: function() {
    return {
      'data-category-id': this.model.get('category_id')
    };
  },
  template: _.template($('#ideaListItemTemplate').html()),

  events: {
    'click .entry-content': 'openIdea',
    'click .comments a': function(event) {
      event.preventDefault();
      this.openIdea();
    },
    'click .event a': function(event) {
      event.preventDefault();
      var form = new EventFormView;
      var $iframe = $('<iframe/>', { src: generateShadowEnvironmentLink('events/create/' + this.model.id) });
      var modal = new Modal('new-event', $iframe);

      modal.$.addClass('loading');
      $iframe.on('load', function() {
        modal.$.removeClass('loading');
      });

      // Focus the title field only if the browser supports placeholder attribute.
      // If it doesn't and we focus the field, then the field would be empty and
      // the user wouldn't have any indication of the field's expected content.
      if ( 'placeholder' in document.createElement('input') ) {
        modal.$.field('title').focus();
      }

      window.eventModal = modal;
    },
    'click .sharing a': function(event) {
      event.preventDefault();
      this.model.share();
    },
    'click .delete a': function(event) {
      event.preventDefault();
      this.deleteIdea();
    },
    'click .vote-action': function(event) {
      event.preventDefault();

      if ( $(event.currentTarget).is('.waiting') ) {
        return;
      }

      if ( this.model.hasBeenVotedFor() ) {
        this.model.removeVote();
      }
      else {
        var $button = $(event.currentTarget).addClass('waiting');

        this.model.vote(function() {
          $button.removeClass('waiting');
        });
      }
    }
  },

  openIdea: function() {
    location.href = '#ideas/' + this.model.id;
  },

  refreshState: function() {
    this.$el.toggleClass('voted', this.model.hasBeenVotedFor());
  },

  updateVoteCount: function() {
    this.$el.attr('data-vote-count', this.model.getVoteCount());
    this.$('.vote-count').text(this.model.getVoteCount());
  },

  deleteIdea: function() {

    if ( confirm(localize('deleteConfirmation')) ) {
      // TODO: Let model.destroy() method take care of the business here.
      $.get('ideas/' + this.model.id + '/delete');
      this.remove();
    }
  },

  render: function() {
    var view = this;
    var data = this.model.toJSON();
    data.comments = this.model.comments;
    data.events = this.model.events.models;
    data.isFinished = this.model.isFinished();
    data.user = Users.get(data.user_id);
    data.hasBeenVotedFor = this.model.hasBeenVotedFor();
    data.voteCount = this.model.getVoteCount();

    this.$el.attr('data-idea-id', this.model.id);
    this.$el.html(this.template(data));
    this.$('.entry-author').append(new TimestampView({ model: this.model }).$el);

    this.refreshState();
    this.updateVoteCount();
    this.$el.toggleClass('popular', this.model.getVoteCount() >= 50);
    this.refreshStatus();

    return this.$el;
  },

  refreshStatus: function() {
    this.$el.attr('data-status', this.model.getStatus().get('code'));
  },

  initialize: function() {
    this.model.view = this;
    this.model.on('change', this.render, this);
    this.model.comments.on('add', this.render, this);
    this.model.events.on('add', this.render, this);
    this.model.votes.on('add remove', function() {
      this.updateVoteCount();
      this.refreshState();
    }, this);
    this.model.statusChanges.on('add', this.refreshStatus, this);
  }
});

$(window).on('message', function(event) {
  Events.add(event.originalEvent.data);
  eventModal.close();
});


var NewIdeaView = Backbone.View.extend({
  tagName: 'li',
  template: _.template($('#newIdeaTemplate').html()),

  events: {
    'click': function() {
      var form = new IdeaFormView;
      var modal = new Modal('new-idea', form);

      // Focus the title field only if the browser supports placeholder attribute.
      // If it doesn't and we focus the field, then the field would be empty and
      // the user wouldn't have any indication of the field's expected content.
      if ( 'placeholder' in document.createElement('input') ) {
        modal.$.field('title').focus();
      }

      modal.$.field('title').characterCounter();

      form.afterSubmit(function() {
        modal.close();
      });
    }
  },

  render: function() {
    this.$el.html(this.template());
  },

  initialize: function() {
    this.render();
  }
});



var IdeaListView = Backbone.View.extend({
  tagName: 'ul',
  id: 'ideas-list',
  className: 'entry-list',

  /**
   * @param {Backbone.Model} idea
   * @param {Boolean} [animate=false]
   */
  renderIdea: function(idea, animate) {
    var view = new IdeaView({ model: idea });
    var $view = view.render().insertAfter(this.$addNewIdea);

    if ( animate === true ) {
      $view.hide().show(500, function() {
        $view.removeAttr('style');
      });
    }
  },

  render: function() {
    this.$el.html('');
    this.$addNewIdea = new NewIdeaView().$el.appendTo(this.$el);
    this.collection.each(this.renderIdea, this);
  },

  resize: function() {
    var MARGIN = 40;
    var viewport = Layout.getViewportDimensions();

    this.$el.width(viewport.width - $('#activitySection').width() - MARGIN);
  },

  initialize: function() {
    this.render();
    this.collection.on('add', function(model) {
      this.renderIdea(model, true);
    }, this);
    this.collection.on('sort', this.render, this);

    this.resize();
    Layout.onResize(this.resize, this);
  }
});
