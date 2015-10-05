
var Ideas = new (Backbone.Collection.extend({
  url: 'ideas'
}));

Ideas.sortByVoteCount = function() {
  Ideas.comparator = function(idea1, idea2) {
    var idea1votes = idea1.getVoteCount();
    var idea2votes = idea2.getVoteCount();

    return ( idea1votes > idea2votes ) ? 1 : ( idea1votes < idea2votes ) ? -1 : 0;
  };

  Ideas.sort();
};

Ideas.sortByDate = function() {
  Ideas.comparator = function(idea1, idea2) {
    var idea1date = idea1.get('created_at');
    var idea2date = idea2.get('created_at');

    return ( idea1date > idea2date ) ? 1 : ( idea1date < idea2date ) ? -1 : 0;
  };

  Ideas.sort();
};

Ideas.model = Backbone.Model.extend({

  initialize: function() {
    this.comments = new Backbone.Collection;
    this.comments.comparator = function(a, b) {
      var timestamp1 = a.get('created_at');
      var timestamp2 = b.get('created_at');

      return ( timestamp1 < timestamp2 ) ? -1 : ( timestamp1 > timestamp2 ) ? 1 : 0;
    };
    this.statusChanges = new Backbone.Collection;
    this.events = new Backbone.Collection;
    this.votes = new Backbone.Collection;
    this.views = new Backbone.Collection;
    this.subscriptions = new Backbone.Collection;
  },

  vote: function(callback) {
    Votes.create({
      user_id: USER_ID,
      idea_id: this.id
    }, {
      wait: true,
      success: callback
    });
  },

  share: function() {
    var data = {
      email: prompt(localize('askRecipientEmail'))
    };

    if ( data.email.isValid('email') ) {
      $.post('ideas/' + this.id + '/share', data, function() {
        alert(localize('thanksForSharing'));
      });
    }
  },

  removeVote: function() {
    if ( confirm(localize('removeVoteConfirmation')) ) {
      this.votes.where({
        user_id: USER_ID
      })[0].destroy();
    }
  },

  subscribe: function() {
    Subscriptions.create({
      idea_id: this.id
    }, {
      wait: true
    });
  },

  unsubscribe: function() {
    Subscriptions.getByIdea(this).destroy();
  },

  userHasSubscribed: function() {
    return this.subscriptions.where({ user_id: USER_ID }).length > 0;
  },

  generateLink: function() {
    return '<a href="#ideas/' + this.id + '">' + this.get('title') + '</a>';
  },

  getVoteCount: function() {
    return this.votes.length;
  },

  hasBeenVotedFor: function() {
    return this.votes.where({ user_id: USER_ID }).length > 0;
  },

  matchesSearchPhrase: function(searchPhrase) {
    var title = this.get('title').toLowerCase();
    var authorName = Users.get(this.get('user_id')).get('name').toLowerCase();

    return title.contains(searchPhrase) || authorName.contains(searchPhrase);
  },

  isFinished: function() {
    return this.getStatusId() == 2;
  },

  getStatus: function() {
    return Statuses.get(this.getStatusId());
  },

  getStatusId: function() {
    var lastStatusChange = this.statusChanges.last();

    return lastStatusChange ? lastStatusChange.get('status_id') : 0;
  }
});
