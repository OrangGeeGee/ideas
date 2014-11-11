
var Router = Backbone.Router.extend({
  routes: {
    'categories/:id': function(id) {
      Categories.get(id).activate().others().invoke('deactivate');
    },
    '*index': function() {
      Categories.first().activate();
    }
  }
});
