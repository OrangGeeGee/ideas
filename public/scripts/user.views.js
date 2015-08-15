


var OnlineUsersListItemView = Backbone.View.extend({
  tagName: 'li',
  activityCheckInterval: null,

  render: function() {
    this.$el.html(this.model.generateProfileImage());
    this.$el.attr('title', this.model.get('name'));
    this.refreshStatus();
  },

  refreshStatus: function() {
    this.$el.attr('class', this.model.getState());
  },

  initialize: function() {
    this.render();

    this.activityCheckInterval = setInterval(this.refreshStatus.bind(this), 5 * 1000);
  }
});



var OnlineUsersListView = Backbone.View.extend({
  el: '#onlineUsersList',

  refresh: function() {
    this.$el.toggleClass('hidden', this.$el.children('.online, .away').length == 0);
  },

  initialize: function() {
    Users.on('change:last_activity_at', function(user) {

      // Don't list the active user.
      if ( user.id == USER_ID ) {
        return;
      }

      if ( !user.statusView ) {
        user.statusView = new OnlineUsersListItemView({ model: user });
        this.$el.append(user.statusView.$el);
        this.refresh();
      }
    }, this);
  }
});
