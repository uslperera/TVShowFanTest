window.DashboardView = Backbone.View.extend({

    initialize: function () {
        console.log('Initializing Home View');
    },

    events: {
        //"click #showMeBtn": "showMeBtnClick"
    },

    render: function () {
        var that = this;
        $.get('dashboard.html', function (data) {
            var template = _.template(data);
            $(that.el).html(template);
        }, 'html');

    },

    showMeBtnClick: function () {
        //app.headerView.search();
    }

});