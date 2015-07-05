
var Events = new Collection(generateShadowEnvironmentLink('events'), function() {
  function addEvent(event) {
    Ideas.get(event.get('idea_id')).events.add(event);
  }

  this.each(addEvent);
  this.on('add', addEvent);
});
