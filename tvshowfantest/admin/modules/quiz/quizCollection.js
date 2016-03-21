var QuizCollection = Backbone.Collection.extend({
    model: QuizModel,
    url: '../index.php/api/quizzes'
});
