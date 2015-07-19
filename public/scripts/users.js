
var Users = new (Backbone.Collection.extend({

}));

Users.model = Backbone.Model.extend({
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
