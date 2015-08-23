
var Router = Backbone.Router.extend({
  routes: {
    'ideas/:id': function(id) {
      new IdeaModalView({ model: Ideas.get(id) });

      IdeaViews.create({
        idea_id: id
      }, {
        wait: true
      });
    }
  }
});
