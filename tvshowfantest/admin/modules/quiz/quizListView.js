var QuizListView = Backbone.View.extend({
  initialize: function() {
    this.template = _.template(templates['quizListView']);
    //load quizzes
    this.collection = new QuizCollection();
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
    //render individual items
    this.renderListItems();
    return this;
  },
  renderListItems: function() {
    this.collection.forEach(function(item) {
      var quizItem = new QuizListItemView({
        quiz: item
      });
      $('tbody').append(quizItem.el);
      quizItem.render();
    });
    return this;
  },
  events: {
    "click #addQuiz": "addQuiz"
  },
  addQuiz: function() {
    var quizView = new QuizView({
      collection: this.collection,
      model: new QuizModel
    });
    $("#modal").html(quizView.el);
  }
});
