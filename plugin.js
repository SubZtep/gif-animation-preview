jQuery(function() {
    var gifs = jQuery('img[data-gif]');
    gifs.imagesLoaded( function() {
        gifs.gifplayer();
    });
});