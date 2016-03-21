var TVShowListView = Backbone.View.extend({
  initialize: function() {
    this.template = _.template(templates['tvShowListView']);
    //load all the tv shows
    this.collection = new TVShowCollection();
    this.collection.fetch({
      reset: true
    });
    this.render();
    this.listenTo(this.collection, 'reset', this.render);
    //refresh the list if collection triggers rest, add, remove events
    var self = this;
    this.collection.bind("reset add remove", function() {
      self.$('tbody').html('');
      self.renderListItems();
    });
  },
  render: function() {
    this.$el.html(this.template());
    this.renderListItems();
    return this;
  },
  renderListItems: function() {
    this.collection.forEach(function(item) {
      var tvShowItem = new TVShowListItemView({
        tvShow: item
      });
      $('tbody').append(tvShowItem.el);
      tvShowItem.render();
    });
    return this;
  },
  events: {
    "click #addTVShow": "addTVShow"
  },
  addTVShow: function() {
    var tvShowView = new TVShowView({
      collection: this.collection,
      model: new TVShowModel
    });
    $("#modal").html(tvShowView.el);
  }
});
