
var Router = Backbone.Router.extend({
  routes: {
    'ideas/:id': function(id) {
      new IdeaModalView({ model: Ideas.get(id) });
      $.get('ideas/' + id + '/read');
    }
  }
});
