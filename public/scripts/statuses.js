
var Statuses = new Backbone.Collection;
var StatusChanges = new (Backbone.Collection.extend({
  initialize: function() {
    this.on('add', function(statusChange) {
      var idea = Ideas.get(statusChange.get('idea_id'));

      if ( idea ) {
        idea.statusChanges.add(statusChange);
      }
    });
  }
}));
