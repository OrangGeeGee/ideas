
var Users = new Collection('users', function() {
  var activeUser = Users.get(USER_ID);
  $('#header').prepend(new UserHeaderView({ model: activeUser }).$el);
});

Users.model = Backbone.Model.extend({
  generateProfileImage: function() {
    return '<div class="profile-image" style="background-image: url(' + this.get('profileImageURL') + ');"/>';
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
