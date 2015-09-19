
var LandingModalView = Backbone.View.extend({
  template: _.template($('#landingModalTemplate').html()),

  render: function() {
    var modal = Modals.open('landingModal');

    modal.setContent(this.template());
    modal.$title.hide();

    modal.$.on('click', '.button-bar a', function(event) {
      event.preventDefault();
      modal.close();
    });

    modal.onClose(function() {
      Settings.save({ landingPageVisited: 1 });
    }, this);
  },

  initialize: function() {
    this.render();
  }
});

var NewReleaseModalView = Backbone.View.extend({

  render: function() {
    var template = _.template($('#newReleaseModalTemplate').html());
    var modal = Modals.open('newReleaseModal');

    modal.setTitle(localize('app.hasBeenUpdated'));
    modal.setContent(template());

    modal.$.on('click', '.button-bar a', function(event) {
      event.preventDefault();
      modal.close();
    });
  },

  initialize: function() {
    this.render();
  }
});
