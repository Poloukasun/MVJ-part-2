$(document).ready(function () {
    $('.hamburger').on('click', function () {
        $('.header-right').toggleClass('clicked');
        $('header').toggleClass('clicked');
    });
});