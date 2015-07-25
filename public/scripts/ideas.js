
var Ideas = new (Backbone.Collection.extend({
  url: 'ideas'
}));

Ideas.model = Backbone.Model.extend({

  initialize: function() {
    this.comments = new Backbone.Collection;
    this.statusChanges = new Backbone.Collection;
    this.events = new Backbone.Collection;
    this.votes = new Backbone.Collection;
  },

  vote: function() {
    Votes.create({
      user_id: USER_ID,
      idea_id: this.id
    });
  },

  removeVote: function() {
    this.votes.where({
      user_id: USER_ID
    })[0].destroy();
  },

  getVoteCount: function() {
    return this.votes.length;
  },

  hasBeenVotedFor: function() {
    return this.votes.where({ user_id: USER_ID }).length > 0;
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

  getStatus: function() {
    return Statuses.get(this.getStatusId());
  },

  getStatusId: function() {
    var lastStatusChange = this.statusChanges.last();

    return lastStatusChange ? lastStatusChange.get('status_id') : 0;
  }
});
