
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
          var idea1votes = idea1.get('votes');
          var idea2votes = idea2.get('votes');

          return idea1votes && idea2votes && idea1votes.length > idea2votes.length;
        }
        else {
          return idea1.get('created_at') < idea2.get('created_at');
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
  { id: 2, name: 'Kronoloogia' }
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
