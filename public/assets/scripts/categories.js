
var Categories = new Collection('categories', function() {
  new CategoriesListView;
});

Categories.model = Backbone.Model.extend({
  activate: function() {
    return this.set('active', true);
  },

  deactivate: function() {
    return this.set('active', false);
  },

  isActive: function() {
    return this.get('active');
  }
});

Categories.getActive = function() {
  return this.where({ active: true }).first();
};

var CategoriesListView = Backbone.View.extend({
  el: '#categories-list',

  render: function() {
    var $list = this.$el;

    Categories.each(function(category) {
      var view = new CategoryView({ model: category });
      $list.append(view.$el);
    });
  },

  initialize: function() {
    this.render();
  }
});

var CategoryView = Backbone.View.extend({
  tagName: 'li',
  template: _.template('<a href="#categories/<%= id %>"><%= name %></a>'),

  render: function() {
    this.$el.html(this.template(this.model.toJSON()));
    this.$el.toggleClass('active', this.model.get('active') === true);
  },

  initialize: function() {
    this.render();
    this.model.on('change:active', this.render, this);
  }
});
