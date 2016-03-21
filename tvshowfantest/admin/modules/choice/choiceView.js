var ChoiceView = Backbone.View.extend({
  initialize: function(options) {
    this.question = options.question;
    this.choice = options.choice;
    this.template = _.template(templates['choiceView']);
  },
  render: function() {
    this.$el.html(this.template(this.choice));
    return this;
  },
  events: {
    'click #deleteChoice': 'deleteChoice',
    'change #choiceTitle': 'setChoiceTitle',
    'change #isCorrect': 'setIsCorrect'
  },
  deleteChoice: function() {
    //remove choice form the array
    var choices = this.question.choices;
    var index = choices.indexOf(this.choice);
    choices.splice(index, 1);
    this.remove();
  },
  setChoice: function() {
    this.model.set({
      title: this.$('#choiceTitle').val()
    });
  },
  setChoiceTitle: function() {
    this.choice.title = this.$('#choiceTitle').val();
  },
  setIsCorrect: function() {
    this.choice.isAnswer = this.$("#isCorrect").is(":checked");
  }
});
