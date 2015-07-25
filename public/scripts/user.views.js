


var UserHeaderView = Backbone.View.extend({
  id: 'headerProfile',
  template: _.template($('#userHeaderTemplate').html()),

  render: function() {
    this.$el.html(this.template(this.model.toJSON()));
  },

  initialize: function() {
    this.render();

    setTimeout(function() {
      this.$el.slideDown('slow');
    }.bind(this));
  }
});



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

  initialize: function() {
    Users.on('change:last_activity_at', function(user) {
      if ( !user.statusView ) {
        user.statusView = new OnlineUsersListItemView({ model: user });
        this.$el.append(user.statusView.$el);
      }
    }, this);
  }
});
