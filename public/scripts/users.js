
var Users = new (Backbone.Collection.extend({

}));

Users.model = Backbone.Model.extend({

  getState: function() {
    var activityTimestamp = this.get('last_activity_at');
    var timeDifference = moment().diff(activityTimestamp, 's');
    var MINUTE = 60;

    if ( timeDifference < 0.5 * MINUTE ) {
      return 'online';
    }

    if ( timeDifference < 10 * MINUTE ) {
      return 'away';
    }

    return 'offline';
  },

  generateProfileImage: function() {
    return '<div class="profile-image" style="background-image: url(' + this.get('profileImageURL') + ');"/>';
  }
});

var UserHeaderView = Backbone.View.extend({
  id: 'headerProfile',
  template: _.template($('#user-header-template').html()),

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
