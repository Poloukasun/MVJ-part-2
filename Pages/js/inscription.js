$(document).ready(function () {
    $('.gender').on('click', (e) => {
        $('.gender.selected').removeClass('selected');
        $(e.target).addClass('selected');
    });
});