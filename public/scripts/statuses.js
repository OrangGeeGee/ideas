
var Statuses = new Backbone.Collection;
var StatusChanges = new (Backbone.Collection.extend({
  initialize: function() {
    this.on('add', function(statusChange) {
      Ideas.get(statusChange.get('idea_id')).statusChanges.add(statusChange);
    });
  }
}));
