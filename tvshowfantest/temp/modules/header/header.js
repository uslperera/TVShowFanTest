var HeaderView = Backbone.View.extend({
    initialize: function () {

    },
    render: function () {
        var that = this;
        $.get('modules/header/header.html', function (data) {
            var template = _.template(data);
            $(that.el).html(template);
        }, 'html');
    }
});