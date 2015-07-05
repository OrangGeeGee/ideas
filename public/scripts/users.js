
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
  },

  hasFreeVotes: function() {
    //return this.get('available_votes') > 0;
    // TEMP: For the moment, users can vote for every idea there is.
    return true;
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

    this.model.on('change:available_votes', this.render, this);
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
