
var Ideas = new Collection('ideas', function() {
  var $ideasList = new IdeaListView({ collection: Ideas }).$el.appendTo('body');
});

Ideas.model = Backbone.Model.extend({
  vote: function() {
    var user = Users.get(USER_ID);
    var data = user.toJSON();
    data.pivot = {
      voted_at: moment().format('YYYY-MM-DD HH:mm:ss')
    };

    this.get('userData').push(data);
    user.decrement('available_votes');
  },

  removeVote: function() {
    var activeUser = Users.get(USER_ID);

    if ( $.isArray(this.get('userData')) ) {
      this.get('userData').forEach(function(user) {
        if ( user.id == USER_ID ) {
          user.pivot.voted_at = '0000-00-00 00:00:00';
        }
      });
    }
  },

  getVoteCount: function() {
    return this.get('userData').filter(function(user) {
      return user.pivot.voted_at != '0000-00-00 00:00:00';
    }).length;
  },

  hasBeenVotedFor: function() {
    return this.get('userData').filter(function(user) {
      return user.id == USER_ID && user.pivot.voted_at != '0000-00-00 00:00:00';
    }).length == 1;
  },

  matchesCategoryFilter: function() {
    return this.get('category_id') == Categories.getActive().id;
  },

  isFinished: function() {
    return this.get('status_id') == 1;
  }
});



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
    this.$el.field('category_id').val(Categories.getActive().id);
    this.toggleSubmitButton();

    if ( !('placeholder' in document.createElement('input')) ) {
      this.$('[placeholder]').mimicPlaceholder();
    }

    return this.$el;
  }
});



var IdeaView = Backbone.View.extend({
  tagName: 'li',
  template: _.template($('#idea-template').html()),

  events: {
    'click .entry-content': 'openIdea',
    'click .comments a': function(event) {
      event.preventDefault();
      this.openIdea();
    },
    'click .delete a': function(event) {
      event.preventDefault();
      this.deleteIdea();
    },
    'click img': function() {
      var author = Users.get(this.model.get('user_id'));
      //new UserProfileView({ model: author });
    }
  },

  openIdea: function() {
    new CommentListView({ model: this.model });
    $.get('ideas/' + this.model.id + '/read');
  },

  deleteIdea: function() {
    if ( confirm('Oled sa kindel, et soovid oma idee kustutada?') ) {
      $.get('ideas/' + this.model.id + '/delete');
      this.remove();
    }
  },

  addVotingAction: function () {
    this.$el.prepend(new VoteView({ model: this.model }).$el);
  },

  render: function() {
    var view = this;
    var data = this.model.toJSON();
    var author = Users.get(this.model.get('user_id'));
    var user = Users.get(USER_ID);
    data.comments = Comments.where({ idea_id: this.model.id });
    data.user = Users.get(data.user_id);

    this.$el.html(this.template(data));

    if ( this.model.isFinished() ) {
      this.$el.addClass('finished');
    }

    this.model.on('change', this.render, this);

    Categories.on('change:active', function() {
      view.$el.toggle(view.model.matchesCategoryFilter());
    });

    if ( author != user && !this.model.isFinished() ) {
      this.addVotingAction();
    }

    return this.$el;
  },

  initialize: function() {
    this.model.view = this;
    this.$el.attr('data-idea-id', this.model.id);
  }
});



var NewIdeaView = Backbone.View.extend({
  tagName: 'li',
  template: _.template($('#new-idea-template').html()),

  events: {
    'click': function() {
      var modal = new Modal('new-idea', new IdeaFormView);

      // Focus the title field only if the browser supports placeholder attribute.
      // If it doesn't and we focus the field, then the field would be empty and
      // the user wouldn't have any indication of the field's expected content.
      if ( 'placeholder' in document.createElement('input') ) {
        modal.$.field('title').focus();
      }

      modal.$.on('submit', function() {
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

    if ( !idea.matchesCategoryFilter() ) {
      $view.hide();
    }
    else if ( animate === true ) {
      $view.hide().show(500);
    }
  },

  render: function() {
    this.$el.html('');
    this.$addNewIdea = new NewIdeaView().$el.appendTo(this.$el);
    this.collection.each(this.renderIdea, this);
  },

  initialize: function() {
    this.render();
    this.collection.on('add', function(model) {
      this.renderIdea(model, true);
    }, this);
    this.collection.on('sort', this.render, this);
  }
});



var VoteView = Backbone.View.extend({
  tagName: 'a',
  className: 'vote-link',

  events: {
    'click': function(event) {
      var user = Users.get(USER_ID);
      event.preventDefault();

      if ( this.model.hasBeenVotedFor() ) {
        $.get(event.target.href);
        this.model.removeVote();
      }
      else if ( user.hasFreeVotes() ) {
        // TODO: Rewrite. Logic should be in the model.
        $.get(event.target.href);
        this.model.vote();
      }

      this.render();
    }
  },

  render: function() {
    this.el.href = this.model.hasBeenVotedFor()
      ? 'ideas/' + this.model.id + '/unvote'
      : 'ideas/' + this.model.id + '/vote';
    this.$el.text(this.model.getVoteCount());
    this.$el.toggleClass('voted', this.model.hasBeenVotedFor());
    this.el.title = this.model.hasBeenVotedFor() ? 'Võta oma hääl tagasi' : 'Anna oma hääl';
  },

  initialize: function() {
    var user = Users.get(USER_ID);

    this.render();

    // TODO: Callback stacks over time. Potential performance impact.
    this.listenTo(user, 'change:available_votes', function() {
      if ( !user.hasFreeVotes() ) {
        this.render();
      }
    }.bind(this));
  }
});
