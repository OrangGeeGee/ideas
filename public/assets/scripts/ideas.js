
var Ideas = new Collection('ideas', function() {
  var $ideasList = new IdeaListView({ collection: Ideas }).$el.appendTo('body');
});

Ideas.model = Backbone.Model.extend({
  defaults: {
    userData: []
  },

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
    this.get('userData').forEach(function(user) {
      if ( user.id == USER_ID ) {
        user.pivot.voted_at = '0000-00-00 00:00:00';
      }
    });
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

  matchesSearchPhrase: function() {
    var searchPhrase = searchField.value.toLowerCase();
    var title = this.get('title').toLowerCase();
    var authorName = Users.get(this.get('user_id')).get('name').toLowerCase();

    if ( searchPhrase == $(searchField).attr('placeholder').toLowerCase() ) {
      searchPhrase = '';
    }

    return !searchPhrase || title.contains(searchPhrase) || authorName.contains(searchPhrase);
  },

  isInProgress: function() {
    return this.get('status_id') == 1;
  },

  isFinished: function() {
    return this.get('status_id') == 2;
  }
});



var IdeaFormView = Backbone.View.extend({
  tagName: 'form',
  template: _.template($('#idea-form-template').html()),
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
    'click .vote a': function(event) {
      event.preventDefault();

      if ( this.model.hasBeenVotedFor() ) {
        $.get(event.target.href);
        this.model.removeVote();
      }
      else if ( Users.get(USER_ID).hasFreeVotes() ) {
        // TODO: Rewrite. Logic should be in the model.
        $.get(event.target.href);
        this.model.vote();
      }

      this.render();
    },
    'click img': function() {
      var author = Users.get(this.model.get('user_id'));
      //new UserProfileView({ model: author });
    }
  },

  openIdea: function() {
    location.href = '#ideas/' + this.model.id;
  },

  deleteIdea: function() {
    if ( confirm('Oled sa kindel, et soovid oma idee kustutada?') ) {
      $.get('ideas/' + this.model.id + '/delete');
      this.remove();
    }
  },

  render: function() {
    var view = this;
    var data = this.model.toJSON();
    data.comments = Comments.where({ idea_id: this.model.id });
    data.user = Users.get(data.user_id);
    data.hasBeenVotedFor = this.model.hasBeenVotedFor();

    this.$el.html(this.template(data));
    this.$('.entry-author').append(new TimestampView({ model: this.model }).$el);
    this.$el.prepend(new VoteCountView({ model: this.model }).$el);

    this.$el.toggleClass('in-progress', this.model.isInProgress());
    this.$el.toggleClass('finished', this.model.isFinished());
    this.$el.toggleClass('popular', this.model.getVoteCount() >= 50);

    this.model.on('change', this.render, this);

    Categories.on('change:active', function() {
      view.$el.toggle(view.model.matchesCategoryFilter() && view.model.matchesSearchPhrase());
    });

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
      var form = new IdeaFormView;
      var modal = new Modal('new-idea', form);

      // Focus the title field only if the browser supports placeholder attribute.
      // If it doesn't and we focus the field, then the field would be empty and
      // the user wouldn't have any indication of the field's expected content.
      if ( 'placeholder' in document.createElement('input') ) {
        modal.$.field('title').focus();
      }

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

    if ( !idea.matchesCategoryFilter() || !idea.matchesSearchPhrase() ) {
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



var VoteCountView = Backbone.View.extend({
  className: 'vote-count',

  render: function() {
    var voteCount = this.model.getVoteCount();
    this.$el.text(voteCount).toggle(voteCount > 0);
  },

  initialize: function() {
    this.render();
  }
});
