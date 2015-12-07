/**
 * Created by jamestrevorlees on 15/12/07.
 */

function isIE () {
    var myNav = navigator.userAgent.toLowerCase();
    return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
}

window.isIEOld = isIE() && isIE() < 9;
window.isiPad = navigator.userAgent.match(/iPad/i);

var img = $('.video').data('placeholder'),
    video = $('.video').data('video'),
    noVideo = $('.video').data('src'),
    el = '';

if($(window).width() > 599 && !isIEOld && !isiPad) {
    el +=   '<video autoplay loop poster="' + img + '">';
    el +=       '<source src="' + video + '" type="video/mp4">';
    el +=   '</video>';
} else {
    el = '<div class="video-element" style="background-image: url(' + noVideo + ')"></div>';
}

$('.video').prepend(el);

