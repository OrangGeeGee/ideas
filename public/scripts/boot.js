
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
    new UserSettingsView({ model: Settings });
  });

  Layout.initialize();

  // Show Hackathon category for non-Estonians.
  if ( !activeUser.get('email').endsWith('.ee') ) {
    $('#category').val(3).trigger('change');
  }

  if ( !activeUser.get('settings').landingPageVisited ) {

    // An idea is open, let's just assume the user understands the
    // purpose of the environment without reading the landing content.
    if ( Modals.active ) {
      Settings.save({ landingPageVisited: 1 });
    }
    else {
      new LandingModalView();
    }
  }

  // Check if there's been a new release.
  // TODO: Introduce an elegant solution.
  else if ( $('#newReleaseModalTemplate').length && !Modals.active ) {
    new NewReleaseModalView();
  }
});
