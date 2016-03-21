var HeaderView = Backbone.View.extend({
  initialize: function(options) {
    this.model = options.model;
    this.template = _.template(templates['headerView']);
  },
  render: function() {
    this.$el.html(this.template);
    this.renderPanel();
    return this;
  },
  renderPanel: function() {
    var self = this;
    //get username in current session
    this.model.getSession(function(data) {
      if (data.status == 'success') {
        var logoutview = new LogoutView({
          username: data.msg,
          model: self.model
        });
        self.$('#logoutpanel').html(logoutview.el);
        //add sub navigation bar
        var subnav = '<ul class="nav navbar-nav sub-nav">' +
          '<li role="presentation" class="active">' +
          '<a href="#quizzes">Quizzes</a>' +
          '</li>' +
          '<li role="presentation">' +
          '<a href="#tvshows">TV Shows</a>' +
          '</li>' +
          '</ul>';
        self.$('#sub-nav').html(subnav);
        logoutview.render();
      }
    });
  }
});
