
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
    Ideas.sortByVoteCount();
  },

  sortByDate: function() {
    Ideas.sortByDate();
  },

  initialize: function() {
    this.$searchField = this.$('#searchField');

    this.filter();
  }
});
