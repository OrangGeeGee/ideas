
var TimestampView = Backbone.View.extend({
  tagName: 'span',
  className: 'timestamp',

  updateTimestamp: function() {
    var MINUTE = 60 * 1000;
    var createdAt = moment(this.model.get('created_at'));
    this.$el.text(createdAt.fromNow());

    // Calculate timeout for the next update. Default frequency is daily.
    var delay = 24 * 60 * MINUTE;

    if ( moment().diff(createdAt, 'hour') < 1 ) {
      delay = MINUTE;
    }
    else if ( moment().diff(createdAt, 'day') < 1 ) {
      delay = 60 * MINUTE;
    }

    setTimeout(this.updateTimestamp.bind(this), delay);
  },

  render: function() {
    this.updateTimestamp();
  },

  initialize: function() {
    this.render();
  }
});
