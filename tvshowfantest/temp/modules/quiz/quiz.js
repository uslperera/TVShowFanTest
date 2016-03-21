var QuizView = Backbone.View.extend({

    question: "",

    events: {
        'click .close': 'close',
        'click #save': 'save',
        'click #addQuestion': 'addQuestion',
        "change #quizTitle": "setQuizTitle",
        "change #quizDescription": "setQuizDescription",
        "change #quizTime": "setQuizTime",
        "change #quizTVShow": "setTVShow"
    },

    initialize: function () {
        this.model = new Quiz();
        var self = this;
        $.get('modules/quiz/quiz.html', function (data) {
            self.template = _.template(data);
            console.log($("#quizTitle"));
            self.quizTitle = $("#quizTitle");
            self.quizDescription = $("#quizDescription");
            self.quizTime = $("#quizTime");
            self.quizTVShow = $("#quizTVShow");
        }, 'html');
        $.get('modules/quiz/question.html', function (data) {
            self.question = data;
        }, 'html');
    },

    render: function () {
        //        this.$el.html(this.template(this.model.toJSON()));
        this.$el.html(this.template);
        return this;
    },

    show: function () {
        $('#modal').append(this.render().el);
        $('#newQuizModal').modal('show');

    },

    close: function () {
        $('#newQuizModal').modal('close');
    },

    addQuestion: function () {
        questions = $('#questionCount').val();
        for (i = 1; i <= questions; i++) {

            $('.questions').append(this.question);
        }
    },
    setQuizTitle: function (e) {
        console.log();
        this.model.set({
            title: $("#quizTitle").val()
        });
    },
    setQuizDescription: function (e) {
        this.model.set({
            description: $("#quizDescription").val()
        });
    },
    setQuizTime: function (e) {
        this.model.set({
            time: $("#quizTime").val()
        });
    },
    setTVShow: function (e) {
        this.model.set({
            tvshow: $("#quizTVShow").val()
        });
    },
    save: function (e) {
        console.log(this.model.toJSON());
    }

});