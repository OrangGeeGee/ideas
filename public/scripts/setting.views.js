


var UserSettingsView = Backbone.View.extend({
  template: _.template($('#userSettingsTemplate').html()),

  events: {
    'click :checkbox': 'tickCheckbox',
    'click button': 'close'
  },

  tickCheckbox: function(event) {
    this.model.save(event.target.name, event.target.checked);
  },

  close: function() {
    this.layer.remove();
    this.remove();
  },

  render: function() {
    this.$el.html(this.template(this.model.toJSON()));
  },

  initialize: function() {
    this.layer = new Layer({
      id: 'userSettingsLayer',
      $target: $('#userName .profile-image'),
      content: this.$el
    });

    this.render();
  }
});
