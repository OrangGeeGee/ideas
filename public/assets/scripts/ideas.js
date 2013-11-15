
var Ideas = new Collection('ideas', function() {
  new IdeaFormView().render().appendTo('body');
});



var IdeaFormView = Backbone.View.extend({
  tagName: 'form',
  template: _.template($('#idea-form-template').html()),

  events: {
    submit: 'submit'
  },

  submit: function(event) {
    event.preventDefault();
    var data = this.$el.parseAsJSON();

    Ideas.create(data);
    this.el.reset();
  },

  render: function() {
    return this.$el.html(this.template());
  }
});
