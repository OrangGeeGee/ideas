
var SortingOptions = new Backbone.Collection;

SortingOptions.on('add', function(sortOption) {
  var view = new SortingItemView({ model: sortOption });
  $('#sorting-options-list').append(view.$el);
});

var SortingItemView = Backbone.View.extend({
  tagName: 'li',
  template: _.template('<a href="#"><%= name %></a>'),

  events: {
    'click': function() {
      this.$el.addClass('active').siblings().removeClass('active');
    },
    'click a': function(event) {
      event.preventDefault();
      var sortingOption = this.model;

      Ideas.comparator = function(idea1, idea2) {
        if ( sortingOption.id == 1 ) {
          var idea1votes = idea1.getVoteCount();
          var idea2votes = idea2.getVoteCount();
          return idea1votes > idea2votes ? 1 : idea1votes < idea2votes ? -1 : 0;
        }
        else {
          var idea1date = idea1.get('created_at');
          var idea2date = idea2.get('created_at');
          return idea1date > idea2date ? 1 : idea1date < idea2date ? -1 : 0;
        }
      };
      Ideas.sort();
    }
  },

  render: function() {
    this.$el.html(this.template(this.model.toJSON()));
  },

  initialize: function() {
    this.render();
  }
});

SortingOptions.add([
  { id: 1, name: 'Populaarsed' },
  { id: 2, name: 'Uusimad ees' }
]);

$(searchField).on('keyup', function(event) {
  var searchPhrase = this.value.toLowerCase();

  // Escape key.
  if ( event.which == 27 ) {
    $(this).val('').trigger('keyup');
  }
  else {
    Ideas.filter(function(idea) {
      var title = idea.get('title').toLowerCase();
      idea.view.$el.toggle(!searchPhrase || title.contains(searchPhrase));
    });
  }
});
