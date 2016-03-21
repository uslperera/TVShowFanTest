var TVShow = Backbone.Collection.extend({
    model: Quiz,
    url: "../index.php/api/quizzes"
});

var TVShowCollection = Backbone.Collection.extend({
    model: Quiz,
    url: "../index.php/api/quizzes"
});