////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// jQuery
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$(document).ready(function($) {

//  Parallax scrolling and fixed header after scroll

    $('#map .marker-style').css('opacity', '.5 !important');
    $('#map .marker-style').css('bakground-color', 'red');

    $(window).scroll(function () {
        if ($(window).width() > 768) {
            if($('#map').hasClass('has-parallax')){
                //$('#map .gm-style > div:first-child > div:first-child').css('margin-top', scrollAmount + 'px'); // old script
                $('#map .gm-style').css('margin-top', scrollAmount + 'px');
                $('#map .leaflet-map-pane').css('margin-top', scrollAmount + 'px');
            }
        }
    });

});

$(window).load(function(){

//  Show Search Box on Map

    $('.search-box.map').addClass('show-search-box');
});

function setMapHeight(){
    var $body = $('body');
    if($body.hasClass('has-fullscreen-map')) {
        $('#map').height($(window).height() - $('.navigation').height());
    }
	$('#map').height($(window).height() - $('.navigation').height());
	var mapHeight = $('#map').height();
	var contentHeight = $('.search-box').height();
	var top;
	top = (mapHeight / 2) - (contentHeight / 2);
	if( !$('body').hasClass('horizontal-search-float') ){
		$('.search-box-wrapper').css('top', top);
	}
    if ($(window).width() < 768) {
        $('#map').height($(window).height() - $('.navigation').height());
    }
}