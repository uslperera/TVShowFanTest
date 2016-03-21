var TVShowModel = Backbone.Model.extend({
  urlRoot: '../index.php/api/tvshows',
  defaults: {
    name: '',
    description: ''
  },
  validate: function(attrs, options) {
    if (attrs.name == '') {
      return "TV Show name cannot be empty";
    }
    if (attrs.description == '') {
      return "TV Show description cannot be empty";
    }
  }
});
