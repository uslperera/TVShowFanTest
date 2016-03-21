var TVShowView = Backbone.View.extend({
  initialize: function() {
    this.template = _.template(templates['tvShowView']);
    this.render();
  },
  render: function() {
    this.$el.html(this.template({
      tvShow: this.model.toJSON()
    }));
    //show modal
    this.$('#newTVShowModal').modal('toggle');
    return this;
  },
  events: {
    'click #saveTVShow': 'saveTVShow',
    'click #closeTVShow': 'closeTVShow',
    'change #tvShowTitle': 'setTVShowTitle',
    'change #tvShowDescription': 'setTVShowDescription'
  },
  saveTVShow: function() {
    //if data is valid
    if (this.model.isValid()) {
      //existing model has an id
      if (this.model.get('id')) {
        //update existing tvshow
        this.model.save();
      } else {
        //save new tvshow
        this.collection.create(this.model);
      }
      //hide modal
      this.$('#newTVShowModal').modal('hide');
      var self = this;
      setTimeout(function() {
        self.remove();
      }, 1000);
    } else {
      toastr.error(this.model.validationError);
    }
  },
  closeTVShow: function() {
    //hide modal
    this.$('#newTVShowModal').modal('hide');
    var self = this;
    setTimeout(function() {
      self.remove();
    }, 1000);
    this.model.fetch();
  },
  setTVShowTitle: function() {
    this.model.set('name', this.$('#tvShowTitle').val());
  },
  setTVShowDescription: function() {
    this.model.set('description', this.$('#tvShowDescription').val());
  }
});
