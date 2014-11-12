
var Ideas = new Collection('ideas', function() {
  var $ideasList = new IdeaListView({ collection: Ideas }).$el.appendTo('body');

  $ideasList.before('<h2>Viimased ideed</h2>');
});

Ideas.model = Backbone.Model.extend({
  vote: function() {
    var user = Users.get(USER_ID);
    this.get('votes').push(user.toJSON());
    user.decrement('available_votes');
  },

  hasBeenVotedFor: function() {
    return _.where(this.get('votes'), { id: USER_ID }).length > 0;
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

    return this.$el;
  }
});



var IdeaView = Backbone.View.extend({
  tagName: 'li',
  template: _.template($('#idea-template').html()),

  events: {
    'click a[href$="/vote"]': function(event) {
      event.preventDefault();

      // TODO: Rewrite. Logic should be in the model.
      $.get(event.target.href);
      this.model.vote();
      this.render();
    },
    'click .entry-content': 'openIdea',
    'click img': function() {
      var author = Users.get(this.model.get('user_id'));
      new UserProfileView({ model: author });
    }
  },

  openIdea: function() {
    var commentList = new CommentListView({ model: this.model });
  },

  addVotingAction: function () {
    var user = Users.get(USER_ID);

    if ( this.model.hasBeenVotedFor() ) {
      this.$el.prepend('<span class="vote">Hääletatud</span>');
    } else if ( user.hasFreeVotes() ) {
      this.$el.prepend('<a class="vote" href="ideas/' + this.model.id + '/vote">Anna hääl</a>');
    }
  },

  render: function() {
    var view = this;
    var data = this.model.toJSON();
    var author = Users.get(this.model.get('user_id'));
    var user = Users.get(USER_ID);
    data.comments = Comments.where({ idea_id: this.model.id });
    data.user = Users.get(data.user_id).toJSON();

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

      // TODO: Callback stacks over time. Potential performance impact.
      this.listenTo(user, 'change:available_votes', function() {
        if ( !user.hasFreeVotes() ) {
          view.render();
        }
      });
    }

    return this.$el;
  },

  initialize: function() {
    this.model.view = this;
  }
});


var NewIdeaView = Backbone.View.extend({
  tagName: 'li',
  template: _.template($('#new-idea-template').html()),

  events: {
    'click': function() {
      var modal = new Modal('new-idea', new IdeaFormView);
      modal.$.field('title').focus();

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
