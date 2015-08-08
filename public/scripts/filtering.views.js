
var FilteringView = Backbone.View.extend({
  el: $('#filter-section'),

  events: {
    'keyup #searchField': 'search',
    'change #category': 'filter',
    'change #sorting': 'sort'
  },

  search: function(event) {
    var searchPhrase = this.$searchField.val().toLowerCase();

    if ( searchPhrase == this.$searchField.attr('placeholder').toLowerCase() ) {
      this.resetSearchField();
      return;
    }

    // Escape key.
    if ( event.which == 27 ) {
      this.resetSearchField();
      return;
    }

    if ( !searchPhrase ) {
      $('#ideas-list > li[style]').removeAttr('style');
      return;
    }

    Ideas.each(function(idea) {
      idea.view.$el.toggle(idea.matchesSearchPhrase(searchPhrase));
    }, this);
  },

  resetSearchField: function() {
    this.$searchField.val('').trigger('keyup');
  },

  filter: function() {
    $('body').attr('data-active-category', this.$('#category').val());
  },

  sort: function() {
    var sortingOption = Number(this.$('#sorting').val());

    switch ( sortingOption ) {
      case 1:
        this.sortByDate();
        break;

      case 2:
        this.sortByVoteCount();
        break;
    }
  },

  sortByVoteCount: function() {
    Ideas.comparator = function(idea1, idea2) {
      var idea1votes = idea1.getVoteCount();
      var idea2votes = idea2.getVoteCount();

      return ( idea1votes > idea2votes ) ? 1 : ( idea1votes < idea2votes ) ? -1 : 0;
    };

    Ideas.sort();
  },

  sortByDate: function() {
    Ideas.comparator = function(idea1, idea2) {
      var idea1date = idea1.get('created_at');
      var idea2date = idea2.get('created_at');

      return ( idea1date > idea2date ) ? 1 : ( idea1date < idea2date ) ? -1 : 0;
    };

    Ideas.sort();
  },

  initialize: function() {
    this.$searchField = this.$('#searchField');

    this.filter();
  }
});
