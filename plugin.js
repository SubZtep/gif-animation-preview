jQuery(function($) {

    var gifs = $('img[data-gif]');
    gifs.imagesLoaded(function() {
        var playerAutoStart = false;
        switch (parseInt(gapParams.type)) {
            case 3: playerAutoStart = true; break;
            case 2: playerAutoStart = gapParams.isSingular == 'yes'; break;
        }
        gifs.gapPlayer({
            autoLoad: playerAutoStart
        });
    });
});