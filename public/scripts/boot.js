
$(function() {
  new Router;
  Backbone.history.start();

  new CategoriesListView;
  new IdeaListView({ collection: Ideas }).$el.appendTo('body');

  var activeUser = Users.get(USER_ID);
  $('#header').prepend(new UserHeaderView({ model: activeUser }).$el);

  DataPoller.initialize();
});
