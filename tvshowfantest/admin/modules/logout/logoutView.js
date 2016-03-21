var LogoutView = Backbone.View.extend({

  initialize: function(options) {
    this.username = options.username;
    this.model = options.model;
    this.template = _.template(templates['logoutView']);
  },
  render: function() {
    this.$el.html(this.template({username: this.username}));
    return this;
  },
  events: {
    'click #logout': 'logout',
  },
  logout: function() {
    //delete session
    this.model.deleteSession(function(data){
      router.navigate('/login', true)
    });
  }
});
