
var Events = new (Backbone.Collection.extend({
  url: generateShadowEnvironmentLink('events'),

  initialize: function() {
    this.on('add', function(event) {
      Ideas.get(event.get('idea_id')).events.add(event);
    });
  }
}));

Events.fetch();

setInterval(function() {
  Events.fetch();
}, 5000);
