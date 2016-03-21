var TVShowListItemView = Backbone.View.extend({
  tagName: 'tr',
  initialize: function(options) {
    this.tvShow = options.tvShow;
    this.template = _.template(templates['tvShowListItemView']);
    //if tvshow is changed render the view
    var self = this;
    this.tvShow.bind("change", function() {
      self.render();
    });
  },
  render: function() {
    this.$el.html(this.template({
      tvShow: this.tvShow.toJSON()
    }));
    return this;
  },
  events: {
    'click #editTVShow': 'editTVShow',
    'click #deleteTVShow': 'deleteTVShow'
  },
  editTVShow: function() {
    var tvShowView = new TVShowView({
      model: this.tvShow
    });
    $("#modal").html(tvShowView.el);
  },
  deleteTVShow: function() {
    var tvShow = this.tvShow;
    //show confirm message
    var confirmMessage = new ConfirmMessage({
      message: {
        title: 'TV Show',
        body: 'Are you sure you want to delete?'
      },
      callback: function() {
        tvShow.destroy();
      }
    });
    $("#modal").html(confirmMessage.el);
  }
});
