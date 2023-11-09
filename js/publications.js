import {
  ajaxRequest,
  viderContainer,
  getCookie,
  ajaxRequestFiles,
} from "./functions.js";
import { handlePubPic } from "./index.js";

const userKey = getCookie("userKey");
let nombrePubs = 0;
let idPub;

function formatRelativeDate(dateStr) {
  const inputDate = new Date(dateStr);
  const currentDate = new Date();

  // Convertir inputDate en heure locale
  const offset = inputDate.getTimezoneOffset();
  inputDate.setMinutes(inputDate.getMinutes() - offset);

  const timeDifference = currentDate - inputDate;

  if (timeDifference < 3600000) {
    // Moins d'une heure
    const minutesAgo = Math.floor(timeDifference / 60000);
    return `il y a ${minutesAgo} minute${minutesAgo > 1 ? "s" : ""}`;
  } else if (timeDifference < 86400000) {
    // Moins d'un jour
    const hoursAgo = Math.floor(timeDifference / 3600000);
    return `il y a ${hoursAgo} heure${hoursAgo > 1 ? "s" : ""}`;
  } else if (timeDifference < 691200000) {
    // Moins de 8 jours
    const daysAgo = Math.floor(timeDifference / 86400000);
    return `il y a ${daysAgo} jour${daysAgo > 1 ? "s" : ""}`;
  } else {
    const options = { day: "2-digit", month: "long", year: "numeric" };
    return inputDate.toLocaleDateString("fr-CA", options);
  }
}

function renderPublication(pub) {
  return `
  <div class="publication">
    <i class="fa-solid fa-ellipsis fa-xl more" idPub="${
      pub.idPublication
    }}"></i>
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
            <div class="date-publication">${formatRelativeDate(
              pub.publicationDate
            )}</div>
        </div>
        </div>
    <div class="titre" dir="auto">${pub.description}</div>
    <div class="image-pub-container" style="background-image: url(js/${
      pub.urlImage
    });">
    </div>
    <div class="likes-comment-container">
        <div class="nb-likes"  dir="auto">
            <i class="fa-solid fa-heart"></i>
            <span idPubLike=${pub.idPublication}>${pub.nbLikes}</span>
        </div>
        <div class="nb-comments" dir="auto">0 commentaires</div>
    </div>
    <hr>
    <div class="actions-pub">
        <div class="aimer" idPub=${pub.idPublication}>
            aimer
            <i class="fa-regular fa-heart" id='c-heart' idPub=${
              pub.idPublication
            }></i>
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

function renderFormPub() {
  return `
  <div class="publication" id="post-form">
      <div class="container">
          <div class="input-container" dir="auto">
              <div class=" left-section">
                  <a href="" class="profil-pic">
                      <div class="profil-pic-container">
                          <img src="no-avatar.jpg" alt="image profil">
                      </div>
                  </a>
              </div>
              <div class="right-section">
                  <textarea id="thought" placeholder="Votre pensée..."></textarea>
              </div>
              <label id="share-btn" class="file-label" title="Publier">
                  <i id="share" class="fa fa-paper-plane" aria-hidden="true"></i>
              </label>
          </div>
          <hr>
          <i class="fa-solid fa-circle-xmark fa-xl cancel none"></i>
          <div class="file-choosed">
              <span class="empty-file-msg">aucun fichier selectionné</span>
          </div>
          <hr>
          <div class="publish">
              <label for="choose-image" class="file-label" title="Choisir une image">
                  <i class="fas fa-image" aria-hidden="true"></i>
              </label>
              <span><strong>Photo/Video</strong></span>
              <label for="choose-video" class="file-label" title="Choisir une vidéo">
                  <i class="fa fa-video-camera" aria-hidden="true"></i>
              </label>
              <input type="file" id="choose-image" name="image" accept="image/*">
              <input type="file" name="video" id="choose-video">
          </div>
      </div>
  </div>
  `;
}

function get() {
  ajaxRequest("GET", "./server/get_publications_sse.php", { userKey: userKey, nbPubs: nombrePubs }, (pubs) => {
      if (pubs.length > 0 && nombrePubs !== pubs.length) {
        nombrePubs = pubs.length;
        viderContainer(".publications-container");
        $(".publications-container").append(renderFormPub());
        handlePubPic();
        publish();
        likePublication();
        pubs.forEach((pub) => {
          $(".publications-container").append(renderPublication(pub));
        });
      }
    }
  );
}

function getPublications() {
  get();
  setInterval(() => {
    get();
  }, 5000);
}

function publish() {
  $("#share-btn").on("click", function () {
    const file = $("#choose-image").prop("files")[0];
    const desc = $("#thought").val();

    if (file && desc.trim()) {
      let formData = new FormData();

      formData.append("file", file);
      formData.append("userKey", userKey);
      formData.append("desc", desc);

      ajaxRequestFiles(formData, "./server/add_publication.php", "POST", (data) => {
        if(data) {
          $("i.cancel").click();
          $("#thought").val("");
          get();
        }
      });
    }
  });
}

function likePublication() {
  $(".aimer").on("click", (e) => {
    idPub = $(e.target).attr('idPub');
    const spanLike = $(`[idPubLike=${idPub}]`);
    ajaxRequest("POST", "./server/like_pub.php", {userKey:userKey,idPub:idPub}, (data) => {
      if(data) {
        $(spanLike).text(data.nbLikes);
      }        
    });
  });
}

$(document).ready(() => {
  getPublications();
  likePublication();
  handlePubPic();
  publish();
});
