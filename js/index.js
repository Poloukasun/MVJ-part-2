import { redirectTo, getCookie, ajaxRequest, viderContainer } from "./functions.js";
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
    getEnregistrement();
});

function getEnregistrement(){
    $('#voirEnregistrement').on('click', (e) => {
        let pubUser = document.getElementById('pubUser');
        let pubEnre = document.getElementById('pubEnre');
        let titreEnre = document.getElementById('titreEnre');
        let button = document.getElementById('voirEnregistrement');
        let AucunePub = document.getElementById('AucunePub');
        pubUser.style.display = 'none';
        pubEnre.style.display = 'flex';

        if(AucunePub != null){
            AucunePub.style.display = 'none';
        }

        titreEnre.style.display = 'block';
        titreEnre.textContent = 'Enregistrements';
        button.innerHTML = '<i class="fas fa-book"></i> Publications';
        ajaxRequest("POST", "./server/get_pub_enregistrer.php", { "userKey": userKey }, (data) => {
            if(data)
            {
                viderContainer('#pubEnre');
                renderEnregistrements(data);
            }
        });
        $('#voirEnregistrement').off('click',this);
        $('#voirEnregistrement').on('click', (e) => {
            pubUser.style.display = 'block';

            if(AucunePub != null){
                AucunePub.style.display = 'block';
            }
            
            pubEnre.style.display = 'none';
            titreEnre.style.display = 'none';
            titreEnre.textContent = 'Publications';
            button.innerHTML = '<i id="font" class="fas fa-bookmark"></i> Enregistrements';
            $('#voirEnregistrement').off('click', this);
            getEnregistrement();
        });
    });
}
function renderEnregistrements(pubs) {
    pubs.forEach(u => {
      $('#pubEnre').append(renderEnregistrement(u))
    });
}
function renderEnregistrement(pub) {
    // console.log(pub);
    // if(pub.isImage == 0)
    // { 
    //     console.log(pub.urlImage);
    //         return `<video class="image-pub-profil" controls loop webkit-playsinline playsinline>
    //         <source src="${pub.urlImage}">
    //         Your browser does not support the video tag.
    //     </video>`;
        
    // }
    if(pub.isImage == 1)
    {
        return `<div class="image-pub-profil" style="background-image: url(${pub.urlImage})"></div>`;
    }
    
}

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