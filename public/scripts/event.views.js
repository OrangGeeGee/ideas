


var EventView = Backbone.View.extend({
  tagName: 'li',
  className: 'event',
  template: _.template($('#eventListItemTemplate').html()),

  render: function() {
    var data = this.model.toJSON();
    data.user = Users.get(data.user_id);

    this.$el.html(this.template(data));
    this.$('.entry-author').append(new TimestampView({ model: this.model }).$el);
  },

  initialize: function() {
    this.render();
  }
});
