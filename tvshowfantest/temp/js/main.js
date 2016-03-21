var AppRouter = Backbone.Router.extend({

    routes: {
        "": "quizzes"
    },

    initialize: function () {
        /*this.headerView = new HeaderView();
        $('.header').html(this.headerView.render().el);

        // Close the search dropdown on click anywhere in the UI
        $('body').click(function () {
            $('.dropdown').removeClass("open");
        });*/
        var headerView = new HeaderView();
        headerView.render();
        $('#header').html(headerView.el);


    },

    quizzes: function () {
        var sidePanelView = new SidePanelView();
        sidePanelView.render();
        $('#nav-side').html(sidePanelView.el);

        var quizList = new QuizListView();
        quizList.render();
        $("#nav-content").html(quizList.el);

    }

});

$(document).ready(function () {
    router = new AppRouter();
    Backbone.history.start();
});

/*
// The Template Loader. Used to asynchronously load templates located in separate .html files
window.templateLoader = {

    load: function (views, callback) {

        var deferreds = [];

        $.each(views, function (index, view) {
            if (window[view]) {
                deferreds.push($.get('partials/' + view + '.html', function (data) {
                    window[view].prototype.template = _.template(data);
                }, 'html'));
            } else {
                alert(view + " not found");
            }
        });

        $.when.apply(null, deferreds).done(callback);
    }

};

templateLoader.load(["DashboardView"],
    function () {
        app = new Router();
        Backbone.history.start();
    });
    */