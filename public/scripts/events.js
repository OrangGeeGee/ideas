
var Events = new (Backbone.Collection.extend({
  url: generateShadowEnvironmentLink('events'),

  initialize: function() {
    this.on('add', function(event) {
      var idea = Ideas.get(event.get('idea_id'));

      if ( idea ) {
        idea.events.add(event);
      }
    });
  }
}));

Events.fetch();

setInterval(function() {
  Events.fetch();
}, 5000);
