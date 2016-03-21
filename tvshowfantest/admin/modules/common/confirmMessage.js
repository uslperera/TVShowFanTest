var ConfirmMessage = Backbone.View.extend({
  initialize: function(options) {
    this.message = options.message;
    this.callback = options.callback;
    this.template = _.template(templates['confirmMessage']);
    this.render();
  },
  render: function() {
    this.$el.html(this.template({
      message: this.message
    }));
    //show modal
    this.$('#confirmModal').modal('toggle');
    return this;
  },
  events: {
    'click #yes': 'yes',
    'click #no': 'no'
  },
  no: function() {
    //hide modal
    this.$('#confirmModal').modal('hide');
    //remove ConfirmMessage
    var self = this;
    setTimeout(function(){ self.remove(); }, 1000);
  },
  yes: function(){
    this.callback();
    //hide modal
    this.$('#confirmModal').modal('hide');
    //remove ConfirmMessage
    var self = this;
    setTimeout(function(){ self.remove(); }, 1000);
  }
});
