var TVShowListView = Backbone.View.extend({

    initialize: function () {
        // this.quiz = new QuizView();
    },

    events: {
        "click #addQuiz": "addQuizBtnClick"
    },

    render: function () {
        var self = this;
        $.get('modules/tvshowList/tvshowList.html', function (data) {
            var template = _.template(data);
//            var quizCollection = new QuizCollection();
//            quizCollection.fetch({
//                success: function (data) {
//                    $(self.el).html(template({
//                        quizzes: data.toJSON()
//                    }));
//
//                }
//            });

        }, 'html');

    },

    addQuizBtnClick: function () {
        //this.quiz.show();
    }

});