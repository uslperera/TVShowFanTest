var QuizListView = Backbone.View.extend({

    initialize: function () {
        this.QuizView = new QuizView();
    },

    events: {
        "click #addQuiz": "addQuizBtnClick"
    },

    render: function () {
        var self = this;
        $.get('modules/quizList/quizList.html', function (data) {
            var template = _.template(data);
            var quizCollection = new QuizCollection();
            quizCollection.fetch({
                success: function (data) {
                    $(self.el).html(template({
                        quizzes: data.toJSON()
                    }));
                }
            });

        }, 'html');

    },

    addQuizBtnClick: function () {
        this.QuizView.show();
    }

});