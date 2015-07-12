
var Users = new Collection('users', function() {
  var activeUser = Users.get(USER_ID);
  $('#header').prepend(new UserHeaderView({ model: activeUser }).$el);
});

Users.model = Backbone.Model.extend({
  getProfileImageURL: function() {
    return isProduction()
      ? USER_PROFILE_IMAGE_PATH + this.id + '.jpg'
      : 'images/placeholder-avatar.jpg';
  },

  generateProfileImage: function() {
    return '<img src="' + this.getProfileImageURL() + '"/>';
  },

  getIdeas: function () {
    return Ideas.where({ user_id: String(this.id) });
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
