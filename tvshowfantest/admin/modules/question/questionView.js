var QuestionView = Backbone.View.extend({
  choices: [],
  initialize: function(options) {
    this.quiz = options.quiz;
    this.question = options.question;
    this.template = _.template(templates['questionView']);
    this.quiz.on('change', this.render, this);
  },
  render: function() {
    this.$el.html(this.template(this.question));

    //include image if it is an image based question
    var self = this;
    if (this.question.image) {
      var image = '<img src="' + 'http://localhost/tvshowfantest/index.php/api/images/' + this.question.image + '" class="img-rounded" alt="" width="200" height="200">';
      this.$('#img').html(image);
    }
    //add choices
    this.renderChoices();
    return this;
  },
  events: {
    'click #addChoice': 'addChoice',
    'change #fileUpload': 'uploadFile',
    'click #deleteQuestion': 'deleteQuestion',
    'change #questionTitle': 'setQuestionTitle'
  },
  addChoice: function() {
    var choice = {
      title: '',
      isAnswer: false
    };
    var choiceView = new ChoiceView({
      choice: choice,
      question: this.question
    });
    //add new choice into question's choices array
    this.question.choices.push(choice);
    this.$("#choices").append(choiceView.el);
    choiceView.render();
  },
  uploadFile: function() {
    //Referred from http://code.tutsplus.com/tutorials/how-to-upload-files-with-codeigniter-and-ajax--net-21684
    var question = this.question;
    var formUrl = "../index.php/api/images/";
    var formData = new FormData();
    //append uploaded file to form data
    formData.append('file', this.$('#fileUpload')[0].files[0]);
    var self = this;
    $.ajax({
      url: formUrl,
      type: 'POST',
      data: formData,
      mimeType: "multipart/form-data",
      contentType: false,
      cache: false,
      processData: false,
      success: function(data, textSatus, jqXHR) {
        var reponse = JSON.parse(data);
        if (reponse.status == "success") {
          question.image = reponse.filename;
          var image = '<img src="' + 'http://localhost/tvshowfantest/index.php/api/images/' + question.image + '" class="img-rounded" alt="" width="200" height="200">';
          //set image
          self.$('#img').html(image);
        }
      }
    });
  },
  deleteQuestion: function() {
    //remove the question
    var questions = this.quiz.get('questions');
    var index = questions.indexOf(this.question);
    questions.splice(index, 1);
    this.remove();
  },
  setQuestionTitle: function() {
    this.question.title = this.$('#questionTitle').val();
  },
  renderChoices: function() {
    var choices = this.question.choices;
    var question = this.question;
    if (choices) {
      var container = document.createDocumentFragment();
      //create choice views
      choices.forEach(function(choice) {
        var choiceView = new ChoiceView({
          choice: choice,
          question: question
        });
        container.appendChild(choiceView.el)
        choiceView.render();
      });
      this.$("#choices").append(container);
    }
  }
});
