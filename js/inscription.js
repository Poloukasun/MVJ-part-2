import {ajaxRequest} from "./functions.js";

$(document).ready(function () {

    const renderInscription = () => $('.container').css('display', 'block');
    const hideInscription = () => $('.container').css('display', 'none');
    const renderLogin = () => $('.container-login').show();
    const hideLogin = () => $(".container-login").hide();
    const renderConfirmation = () => $('.confirmation').css('display', 'block'); 
    
    const getUser = () => {
        let user = {};
        user.firstName = $('#firstName').val();
        user.secondName = $('#secondName').val();
        user.email = $('#email').val();
        user.pass = $('#pass').val();
        user.birthDate = $('#birthday').val();
        user.gender = $('.gender.selected').attr('data');

        return user;
    };

    $('.gender').on('click', (e) => {
        $('.gender.selected').removeClass('selected');
        $(e.target).addClass('selected');
    });

    $('#create-btn').on('click', (e) => {
        e.preventDefault();
        renderInscription();
        hideLogin();
    });

    $('.signup-button').on('click', () => {
        const user = getUser();
        ajaxRequest("POST", "./server/add_user.php", {"data":user}, (data) => {
            hideInscription();
            renderConfirmation();
            $('.confirmation').append(`
                <p>Un courriel de confirmation a été envoyé à l'adresse <strong>${user.email}</strong> Veuillez vérifier votre boîte de réception et cliquer sur le lien de confirmation pour activer votre compte!.</p>
            `);
        });
    });
    
    $('#cancel').on('click', () => {
        renderLogin();
        hideInscription();
    });

    


});