var LoginView = Backbone.View.extend({
    initialize: function() {
        this.template = _.template(templates['loginView']);
    },
    render: function() {
        this.model = new SessionModel;
        this.$el.html(this.template);
        return this;
    },
    events: {
        'click #login': 'login',
        'change #username': 'setUsername',
        'change #password': 'setPassword',
        'keypress #username': 'enterPress',
        'keypress #password': 'enterPress'
    },
    login: function() {
        //create session
        this.model.create(this.model);
    },
    setUsername: function() {
        this.model.set('username', this.$('#username').val())
    },
    setPassword: function() {
        this.model.set('password', this.$('#password').val());
    },
    enterPress: function(e) {
        if (e.which === 13) {
            this.model.set('username', this.$('#username').val())
            this.model.set('password', this.$('#password').val());
            this.login();
        }
    }
});