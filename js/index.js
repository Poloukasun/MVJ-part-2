import { redirectTo, getCookie, ajaxRequest, viderContainer } from "./functions.js";
$("#back-color").hide();

const userKey = getCookie("userKey");

export function handlePubPic() {
    $('#choose-image').change((e) => {
        changeFile('#choose-video', e);
    });

    $("#choose-video").change((e) => {
        changeFile('#choose-image', e);
    });

    function changeFile(fileId, e) {


        let file = e.target.files[0];
        if (file) {
            $(".file-choosed").empty().attr("id", "choosed");
            $(fileId).val(''); // remove current file
            $("i.cancel.none").toggleClass("none");

            let inputName = e.target.name;
            if (inputName == "image") {
                let reader = new FileReader();
                reader.onload = ((e) => {
                    $(".file-choosed").css('background-image', 'url(' + e.target.result + ')');
                });
                reader.readAsDataURL(file);
            } else if (inputName == "video") {
                let fileUrl = URL.createObjectURL(file);
                if (fileUrl) {
                    $(".file-choosed").append(`
                    <video width="90%" controls loop webkit-playsinline playsinline>
                        <source src="${fileUrl}">
                        Your browser does not support the video tag.
                    </video>`);
                    let video = $(".file-choosed>video")[0];
                    video.load();
                    $(".publications-container").scroll(function () {
                        if (!video.paused) {
                            video.pause();
                        }
                    });
                }
            }
            let container = document.querySelector(".container-feed-group");
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }
    }

    $('i.cancel').off().click(() => {
        $('#choose-image').val(''); // remove file
        $('#choose-video').val(''); // remove file
        $(".file-choosed").empty().css('background-image', 'none').removeAttr('id'); // empty container / remove image / remove id=choosed
        $("i.cancel").toggleClass("none"); // x display none
        $(".file-choosed").append("<span class='empty-file-msg'>aucun fichier selectionné</span>"); // ajouté le texte
    });
}


$(document).ready(() => {
    getEnregistrement();
});

function getEnregistrement() {
    $('#voirEnregistrement').on('click', (e) => {
        let pubUser = document.getElementById('pubUser');
        let pubEnre = document.getElementById('pubEnre');
        let titreEnre = document.getElementById('titreEnre');
        let button = document.getElementById('voirEnregistrement');
        let AucunePub = document.getElementById('AucunePub');
        pubUser.style.display = 'none';
        pubEnre.style.display = 'flex';

        if (AucunePub != null) {
            AucunePub.style.display = 'none';
        }

        titreEnre.style.display = 'block';
        titreEnre.textContent = 'Enregistrements';
        button.innerHTML = '<i class="fas fa-book"></i> Publications';
        ajaxRequest("POST", "./server/get_pub_enregistrer.php", { "userKey": userKey }, (data) => {
            if (data) {
                viderContainer('#pubEnre');
                renderEnregistrements(data);
            }
        });
        $('#voirEnregistrement').off('click', this);
        $('#voirEnregistrement').on('click', (e) => {
            pubUser.style.display = 'block';

            if (AucunePub != null) {
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
    $(".titre").off().on("click", (e) => {
        window.location.href = "./index.php?id=" + e.target.getAttribute("idpub");
    });
    let videos = document.querySelectorAll("video");
    videos.forEach(video => {
        video.load();
        video.addEventListener("click", () => {
            videos.forEach(otherVideo => {
                if (otherVideo !== video && !otherVideo.paused) {
                    otherVideo.pause();
                }
            });
        });
    });

    $(document).scroll(function () {
        videos.forEach((video) => {
            if (!video.paused) {
                video.pause();
            }
        });
    });
}
function renderEnregistrement(pub) {
    console.log(pub);
    if (pub.isImage == 0) {
        console.log(pub.urlImage);
        return `
        <div class="image-pub-profil">
        <div class="titre" title="Voir la publication" style="border-top: 1px solid rgb(173, 173, 173); top:-1px" idpub="${pub.idPub}">${pub.description}</div>
        <video controls loop webkit-playsinline playsinline>
            <source src="${pub.urlImage}">
            Your browser does not support the video tag.
        </video>
        </div>`;

    }
    if (pub.isImage == 1) {
        return `<div class="image-pub-profil" title="Voir la publication" style="background-image: url(${pub.urlImage})">
                                <div class="titre" idpub="${pub.idPub}">${pub.description}</div>
                            </div>`;
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
        setCookie("couleur_background", e.target.value, 365);
    });
}

function setCookie(nom, valeur, jours) {
    var date = new Date();
    date.setTime(date.getTime() + (jours * 24 * 60 * 60 * 1000));
    var expires = "expires=" + date.toUTCString();
    document.cookie = nom + "=" + valeur + "; " + expires + "; path=/";
}