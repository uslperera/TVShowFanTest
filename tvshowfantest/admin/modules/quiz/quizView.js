var QuizView = Backbone.View.extend({
  quiz: {
    questions: []
  },
  initialize: function() {
    this.template = _.template(templates['quizView']);
    this.tvShows = new TVShowCollection();
    //Load tvshows
    this.tvShows.fetch({
      reset: true
    });
    this.listenTo(this.tvShows, 'reset', this.render);
  },
  render: function() {
    this.$el.html(this.template({
      quiz: this.model.toJSON()
    }));

    this.renderQuestions();
    this.renderTVShows();

    //Show modal
    this.$('#newQuizModal').modal('toggle');
    this.questionCount = this.$('#questionCount');
    return this;
  },
  events: {
    'click #addQuestion': 'addQuestion',
    'click #saveQuiz': 'saveQuiz',
    'click #closeQuiz': 'closeQuiz',
    'change #quizTitle': 'setQuizTitle',
    'change #quizDescription': 'setQuizDescription',
    'change #quizTime': 'setQuizTime',
    'change #quizTVShow': 'setQuizTVShow'
  },
  addQuestion: function() {
    var question = {
      title: '',
      image: '',
      choices: []
    };
    var questionView = new QuestionView({
      quiz: this.model,
      question: question
    });
    this.model.get('questions').push(question);
    this.$("#questions").append(questionView.el);
    questionView.render();
  },
  saveQuiz: function() {
    //check if quiz is valid
    if (this.model.isValid()) {
      //model has an id if it already exists
      if (this.model.get('id')) {
        //update existing model
        this.model.save();
      } else {
        //create new model
        this.collection.create(this.model);
      }
      //hide modal
      this.$('#newQuizModal').modal('hide');
      var self = this;
      setTimeout(function() {
        self.remove();
      }, 1000);

    } else {
      toastr.error(this.model.validationError);
    }
  },
  closeQuiz: function() {
    //hide modal
    this.$('#newQuizModal').modal('hide');
    var self = this;
    setTimeout(function() {
      self.remove();
    }, 1000);
    //discard changes done
    this.model.fetch();
  },
  setQuizTitle: function() {
    this.model.set('title', this.$('#quizTitle').val());
  },
  setQuizDescription: function() {
    this.model.set('description', this.$('#quizDescription').val());
  },
  setQuizTime: function() {
    this.model.set('time', this.$('#quizTime').val());
  },
  setQuizTVShow: function() {
    //set tvshow to null if default is selected
    if(this.$('#quizTVShow').val()==''){
      this.model.set('tvshow', null);
    }else{
      this.model.set('tvshow', this.$('#quizTVShow').val());
    }
  },
  renderQuestions: function() {
    var questions = this.model.get('questions');
    var quiz = this.model;
    if (questions) {
      var container = document.createDocumentFragment();
      //add all the questions
      questions.forEach(function(question) {
        var questionView = new QuestionView({
          quiz: quiz,
          question: question
        });
        container.appendChild(questionView.el);
        questionView.render();
      });
      this.$("#questions").append(container);
    }
  },
  renderTVShows: function() {
    //add tvshows into the select
    var self = this;
    this.tvShows.each(function(tvshow) {
      self.$('#quizTVShow').append('<option value="' + tvshow.get('id') + '">' + tvshow.get('name') + '</option>');
    });
    this.$('#quizTVShow').val(this.model.get('tvshow'));
  }
});
