var SidePanelView = Backbone.View.extend({
    initialize: function () {

    },
    render: function () {
        var that = this;
        $.get('modules/side-panel/side-panel.html', function (data) {
            var template = _.template(data);
            $(that.el).html(template);
        }, 'html');
    },
    events: {
        "click #side-panel a": "setActive"
    },
    setActive: function (e) {
        $(this).addClass('custom-active')
        console.log(e)
    }

});