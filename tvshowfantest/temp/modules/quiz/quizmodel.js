var Quiz = Backbone.Model.extend({
    defaults: {
        id: 1,
        title: 'Sample Quiz',
        description: 'Description',
        time: 120,
        tvshow: 1,
        questions: []
    }
});
/*
var Choice = Backbone.Model.extend({
    defaults: {
        id: 1,
        title: 'Sample Choice',
        is_answer: true
    }
});

var ChoiceCollection = Backbone.Collection.extend({
    model: Choice,
    url: "../index.php/api/quizzes"
});

var Question = Backbone.Model.extend({
    defaults: {
        id: 1,
        title: 'Sample Question',
        image: '1.png'
    }
});

var QuestionCollection = Backbone.Collection.extend({
    model: Question,
    url: "../index.php/api/quizzes"
});*/