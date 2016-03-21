var SessionModel = Backbone.Model.extend({
  defaults: {
    username: '',
    password: ''
  },
  initialize: function() {
    this.url = '../index.php/api/session'
  },
  create: function() {
    //create session
    $.ajax({
      url: this.url,
      type: 'POST',
      contentType: "application/json",
      data: JSON.stringify(this.toJSON()),
      success: function(data) {
        if (data.status == 'success') {
          //save session key
          localStorage.setItem("tvshowadmin", data.msg);
          //send session key for all requests
          $.ajaxSetup({
            headers: {
              "accept": "application/json",
              "token": localStorage.getItem("tvshowadmin")
            }
          });
          router.navigate('/quizzes', true)
        } else {
          //display toastr message
          toastr.error(data.msg);
        }
      }
    });
  },
  getSession: function(callback) {
    $.ajax({
      url: this.url,
      type: 'GET',
      success: function(data) {
        callback(data);
      },
      error: function(data) {
        router.navigate('/login', true)
      }
    });
  },
  deleteSession: function(callback) {
    $.ajax({
      url: this.url,
      type: 'DELETE',
      success: function(data) {
        callback(data);
      }
    });
  }
});
