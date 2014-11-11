
var Users = new Collection('users');

Users.model = Backbone.Model.extend({
  getIdeas: function () {
    return Ideas.where({ user_id: String(this.id) });
  },

  hasFreeVotes: function() {
    return this.get('available_votes') > 0;
  }
});

var UserProfileView = Backbone.View.extend({
  render: function() {
    var modal = Modals.open('profile');
    var ideas = this.model.getIdeas();

    modal.setContent(
      '<div id="profile-image" style="background-image: url(' + USER_PROFILE_IMAGE_PATH + this.model.id + '.jpg)"/>' +
      '<h2>' + this.model.get('name') + '</h2>'
    );

    if ( ideas.length ) {
      var $list = $('<ul/>').appendTo(modal.$body);

      ideas.forEach(function(idea) {
        $list.append('<li>' + idea.get('title') + '</li>');
      });

      modal.$body.append($list);
    }
  },

  initialize: function() {
    this.render();
  }
});
