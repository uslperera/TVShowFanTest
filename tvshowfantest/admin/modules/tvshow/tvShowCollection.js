var TVShowCollection = Backbone.Collection.extend({
    model: TVShowModel,
    url: '../index.php/api/tvshows'
});
