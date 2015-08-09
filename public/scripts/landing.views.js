
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
      $.post('users/settings', { landingPageVisited: 1 });
    }, this);
  },

  initialize: function() {
    this.render();
  }
});
