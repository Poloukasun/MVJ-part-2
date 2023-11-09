import { ajaxRequest, viderContainer, getCookie } from "./functions.js";

const userKey = getCookie("userKey");
const valeur = document.querySelector('#idGroup');
let nombrePubs = 0;
let pubs = [];

function formatRelativeDate(dateStr) {
  const inputDate = convertUTCDateToLocalDate(new Date(dateStr));
  const currentDate = new Date();
  const timeDifference = currentDate - inputDate;

  if (timeDifference < 3600000) { // Moins d'une heure
    const minutesAgo = Math.floor(timeDifference / 60000);
    return `il y a ${minutesAgo} minute${minutesAgo > 1 ? 's' : ''}`;
  } else if (timeDifference < 86400000) { // Moins d'un jour
    const hoursAgo = Math.floor(timeDifference / 3600000);
    return `il y a ${hoursAgo} heure${hoursAgo > 1 ? 's' : ''}`;
  } else if (timeDifference < 691200000) { // Moins de 8 jours
    const daysAgo = Math.floor(timeDifference / 86400000);
    return `il y a ${daysAgo} jour${daysAgo > 1 ? 's' : ''}`;
  } else {
    const options = { day: '2-digit', month: 'long', year: 'numeric' };
    return inputDate.toLocaleDateString('fr-FR', options);
  }
}

// Source : https://stackoverflow.com/questions/6525538/convert-utc-date-time-to-local-date-time
function convertUTCDateToLocalDate(date) {
  var newDate = new Date(date.getTime() + date.getTimezoneOffset() * 60 * 1000);

  var offset = date.getTimezoneOffset() / 60;
  var hours = date.getHours();

  newDate.setHours(hours - offset);

  return newDate;
}

function renderPublicationGroup(pub) {
  return `
  <div class="publication">
    <i class="fa-solid fa-ellipsis fa-xl more" idPub="${pub.idPublication}}"></i>
    <div class="dropdown-menu">
      <ul>
        <li>
        <i>
        Masquer &nbsp
        </i>
          <i class="fa-regular fa-eye-slash"></i>
        </li>
        <li>
        <i>
          Enregisgter &nbsp
        </i>
          <i class="fa-regular fa-bookmark"></i>
        </li>
        <li>
        <i>
        Supprimer &nbsp
        </i>  
          <i class="fa-solid fa-xmark fa-lg"></i>
        </li>
      </ul>
    </div>
    <div class="infos-publication">
        <a href="" class="profil-pic">
            <div class="profil-pic-container">
                <img src="./profil-default.jpg" alt="image profil">
            </div>
        </a>
        <div class="container-nom-date">
            <div class="profil-name">
                <a href="">
                    ${pub.firstName}
                    ${pub.lastName}
                </a>
            </div>
            <div class="date-publication">${formatRelativeDate(pub.publicationDate)}</div>
        </div>
        </div>
    <div class="titre" dir="auto">${pub.description}</div>
    <div class="image-pub-container" style="background-image: url(js/${pub.urlImage});">
    </div>
    <div class="likes-comment-container">
        <div class="nb-likes"  dir="auto">
            <i class="fa-solid fa-heart"></i>
            0
        </div>
        <div class="nb-comments" dir="auto">0 commentaires</div>
    </div>
    <hr>
    <div class="actions-pub">
        <div class="aimer" id=${pub.idPublication}>
            aimer
            <i class="fa-regular fa-heart" class='c-heart' id=${pub.idPublication}></i>
        </div>
        <div class="commenter">
            commenter
            <i class="fa-regular fa-comment"></i>
        </div>
        <div class="partager">
            partager
            <i class="fa-regular fa-share-from-square"></i>
        </div>
    </div>
    </div>
    `;
}

function get() {
  const idGroup1 = valeur.getAttribute("idGroup");
  ajaxRequest("GET", "./server/get_publications_groups.php", { /*userKey: userKey, nbPubs: nombrePubs,*/ idgroup: idGroup1 }, (pubs) => {
    if (pubs.length > 0 && nombrePubs !== pubs.length) {
      nombrePubs = pubs.length;
      viderContainer(".publications-container");
      pubs.forEach((pub) => {
        $(".publications-container").append(renderPublicationGroup(pub));
      });
    }
  });
}

function getPublications() {
  get();
  setInterval(() => {
    get();
  }, 5000);
}

$(document).ready(() => {

  function likePublication() {
    $('.aimer').on('click', (e) => {

      // ajaxRequest("POST", ""
    });
  }

  $("#share-btn").on("click", function () {
    const file = $("#choose-image").prop("files")[0];
    const desc = $("#thought").val();
    const idGroup1 = valeur.getAttribute("idGroup");
    if (file && desc.trim()) {
      let formData = new FormData();
      formData.append("file", file);
      formData.append("userKey", userKey);
      formData.append('desc', desc);
      formData.append('idGroup', idGroup1);

      $.ajax({
        url: "./server/add_publication_group.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
          if (data) {
            $('i.cancel').click();
            $('#thought').val('');
            get();
          }
        }
      });
    }


  });
  getPublications();
  likePublication();

});