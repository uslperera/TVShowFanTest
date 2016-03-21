var QuizListItemView = Backbone.View.extend({
  tagName: 'tr',
  initialize: function(options) {
    this.quiz = options.quiz;
    this.template = _.template(templates['quizListItemView']);
    //if model is changed render the view
    var self = this;
    this.quiz.bind("change", function() {
      self.render();
    });
  },
  render: function() {
    this.$el.html(this.template({
      quiz: this.quiz.toJSON()
    }));

    //initialize toggle
    this.$('#toggleActive').bootstrapToggle();
    this.$('#toggleActive').prop('checked', this.quiz.get('isActive')).change();

    //save quiz state when toggled
    var self = this;
    this.$('#toggleActive').on('change', function() {
      self.quiz.set('isActive', self.$('#toggleActive').prop('checked'));
      self.quiz.save();
    });

    return this;
  },
  events: {
    'click #editQuiz': 'editQuiz',
    'click #deleteQuiz': 'deleteQuiz'
  },
  editQuiz: function() {
    var quizView = new QuizView({
      model: this.quiz
    });
    $("#modal").html(quizView.el);
  },
  deleteQuiz: function() {
    var quiz = this.quiz;
    //show confirm message box
    var confirmMessage = new ConfirmMessage({
      message: {
        title: 'Quiz',
        body: 'Are you sure you want to delete?'
      },
      callback: function() {
        quiz.destroy();
      }
    });
    $("#modal").html(confirmMessage.el);
  }
});
