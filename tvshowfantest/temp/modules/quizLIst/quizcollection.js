var QuizCollection = Backbone.Collection.extend({
    model: Quiz,
    url: "../index.php/api/quizzes"
});