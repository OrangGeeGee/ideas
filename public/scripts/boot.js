
$(function() {
  new Router;
  Backbone.history.start();

  new OnlineUsersListView;
  new CategoriesListView;
  new ActivityListView().$el.appendTo('#activitySection');
  new IdeaListView({ collection: Ideas }).$el.appendTo('#container');

  var activeUser = Users.get(USER_ID);
  $('#header').prepend(new UserHeaderView({ model: activeUser }).$el);
});
