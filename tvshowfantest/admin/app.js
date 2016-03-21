var AppRouter = Backbone.Router.extend({

  routes: {
    "login": "login",
    "quizzes": "quizzes",
    "tvshows": "tvshows",
    "": "quizzes"
  },

  initialize: function() {
    //load session
    this.session = new SessionModel;
    this.headerView = new HeaderView({
      model: this.session
    });
    $('#header').html(this.headerView.el);
  },
  quizzes: function() {
    this.headerView.render();
    var quizzes = new QuizCollection();
    var quizList = new QuizListView({
      collection: quizzes
    });
    $("#content").html(quizList.el);
  },
  tvshows: function() {
    this.headerView.render();
    var tvshows = new TVShowCollection();
    var tvShowList = new TVShowListView({
      collection: tvshows
    });
    $("#content").html(tvShowList.el);
  },
  login: function() {
    var loginView = new LoginView({
      model: this.session
    });
    $("#content").html(loginView.el);
    this.headerView.render();
    loginView.render();
  }
});
//AppRouter EOC


var htmlViews = [{
  name: 'headerView',
  path: 'modules/header/headerView.html'
}, {
  name: 'loginView',
  path: 'modules/login/loginView.html'
}, {
  name: 'logoutView',
  path: 'modules/logout/logoutView.html'
}, {
  name: 'confirmMessage',
  path: 'modules/common/confirmMessage.html'
}, {
  name: 'quizListView',
  path: 'modules/quiz/quizListView.html'
}, {
  name: 'quizListItemView',
  path: 'modules/quiz/quizListItemView.html'
}, {
  name: 'quizView',
  path: 'modules/quiz/quizView.html'
}, {
  name: 'questionView',
  path: 'modules/question/questionView.html'
}, {
  name: 'choiceView',
  path: 'modules/choice/choiceView.html'
}, {
  name: 'tvShowView',
  path: 'modules/tvshow/tvShowView.html'
}, {
  name: 'tvShowListView',
  path: 'modules/tvshow/tvShowListView.html'
}, {
  name: 'tvShowListItemView',
  path: 'modules/tvshow/tvShowListItemView.html'
}];

var templates = [];

//load all the views
function loadViews(callback) {
  var deferreds = [];
  //load each html view
  htmlViews.forEach(function(view) {
    //add the promise to the array
    deferreds.push($.get(view.path, function(template) {
      templates[view.name] = template;
    }, 'html'));
  });
  //when all the promises are returned execute callback
  $.when.apply(null, deferreds).done(callback);
}

$(document).ready(function() {
  loadViews(function() {
    $.ajaxSetup({
      headers: {
        "accept": "application/json",
        "token": localStorage.getItem("tvshowadmin")
      }
    });
    //Referred from http://stackoverflow.com/questions/17989961/jquery-catch-any-ajax-error
    $(document).ajaxError(function(event, jqxhr, settings, exception) {
      //if an unauthorized access, redirect to login page
      if (jqxhr.status == 401) {
        setTimeout(function() {
          router.navigate('/login', true);
        }, 500);
      }
    });
    //initialize router
    router = new AppRouter();
    Backbone.history.start();
  });
});
