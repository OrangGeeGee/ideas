
var DataPoller = {
  fetch: function() {
    $.getJSON('update', function(response) {
      $.each(response, function(module, data) {

        if ( module == 'UserActivity' ) {
          module = 'Users';
        }

        window[module].add(data, {
          merge: true
        });
      });
    });
  },

  initialize: function() {
    setInterval(this.fetch, 5000);
  }
};
