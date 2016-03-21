var QuizModel = Backbone.Model.extend({

  urlRoot: '../index.php/api/quizzes',
  defaults: {
    title: '',
    description: '',
    time: '',
    isActive: false,
    tvshow: null,
    questions: []
  },
  validate: function(attrs, options) {
    if (attrs.title == '') {
      return "Quiz title cannot be empty";
    }
    if (attrs.time < 60) {
      return "Minimum number of seconds allowed is 60";
    }
    if (attrs.questions.length < 5) {
      return "Minimum number of questions allowed is 5";
    }
    var error = "";
    //validate questions
    attrs.questions.forEach(function(question) {

      if (question.title == '') {
        error = "Question title cannot be empty";
        return;
      }
      if (question.choices.length < 2) {
        console.log('choi');
        error = "Minimum number of choices allowed is 2";
        return;
      }
      //validate choices in each question
      var correctAns = 0;
      question.choices.forEach(function(choice) {
        if (choice.title == '') {
          error = "Choice title cannot be empty";
          return;
        }
        if (choice.isAnswer) {
          correctAns++;
          return;
        }
      });
      if (correctAns == 0 && error=='') {
        error = "There must be atleast one correct answer";
        return;
      }
    });
    if (error != "") {
      return error;
    }
  }
});
