
var Router = Backbone.Router.extend({
  routes: {
    'ideas/:id': function(id) {
      new CommentListView({ model: Ideas.get(id) });
      $.get('ideas/' + id + '/read');
    }
  },

  initialize: function() {
    Categories.first().activate();
    SortingOptions.first().activate();
    $('#notWorkingMessage').slideUp();
  }
});
