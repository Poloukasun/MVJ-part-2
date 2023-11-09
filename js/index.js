import { redirectTo, getCookie } from "./functions.js";
$("#back-color").hide();

const userKey = getCookie("userKey");

export function handlePubPic() {
    $('#choose-image').change((e) => {
        $(".file-choosed").empty().attr("id", "choosed");
        let file = e.target.files[0];
        if (file) {
            $("i.cancel.none").toggleClass("none");
            let reader = new FileReader();
            reader.onload = ((e) => {
                $(".file-choosed").css('background-image', 'url(' + e.target.result + ')');
            });
            reader.readAsDataURL(file);
        }
    });

    $('i.cancel').click(() => {
        $('#choose-image').val(''); // remove file
        $(".file-choosed").empty().css('background-image', 'none').removeAttr('id'); // empty container / remove image / remove id=choosed
        $("i.cancel").toggleClass("none"); // x display none
        $(".file-choosed").append("<span class='empty-file-msg'>aucun fichier selectionné</span>"); // ajouté le texte
    });
}


$(document).ready(() => {

});

export function redirectToUserProfil() {
    $('.profil').on('click', (e) => {
        // const userKey = $(e.target).parent().attr('userKey');
        // redirectTo("profil",`?userKey=${userKey}`);
    });
}

$('.btn-modify').click(() => {
    redirectTo("./modif");
});

$("#back").click((e) => {
    if (e.target.checked) {
        $("#back-color").show();
        handleBackgroundProfil();
    } else {
        $("#back-color").hide();
    }
});

function handleBackgroundProfil() {
    $('#back-color').change((e) => {
        $('.profile-header').css('background-color', e.target.value);
    });
}