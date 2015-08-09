
$(function() {
  new Router;
  Backbone.history.start();

  new OnlineUsersListView;
  new FilteringView;
  new ActivityListView().$el.appendTo('#activitySection');
  new IdeaListView({ collection: Ideas }).$el.appendTo('#container');

  var activeUser = Users.get(USER_ID);
  $('#userName').append(activeUser.generateProfileImage());

  $('#userName').on('click', function() {
    new UserSettingsView({ model: activeUser });
  });

  Layout.initialize();

  // Show Swedish Hackathon category for Swedes by default.
  if ( activeUser.get('email').endsWith(['.se', '.com']) ) {
    $('#category').val(3).trigger('change');
  }

  if ( !activeUser.get('settings').landingPageVisited ) {
    new LandingModalView();
  }
});
