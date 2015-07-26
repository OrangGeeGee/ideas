
var Activities = new (Backbone.Collection.extend({
  initialize: function() {

    function add(item) {
      if ( item.get('created_at') > Activities.minimumDate ) {
        Activities.add(item);
      }
    }

    Comments.on('add', add);
    Ideas.on('add', add);
  }
}));

Activities.comparator = function(a, b) {
  var timestamp1 = a.get('created_at');
  var timestamp2 = b.get('created_at');

  return ( timestamp1 < timestamp2 ) ? -1 : ( timestamp1 > timestamp2 ) ? 1 : 0;
};

Activities.minimumDate = moment().subtract(1, 'week').format('YYYY-MM-DD');
