
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
