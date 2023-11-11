import { ajaxRequest, viderContainer, getCookie, ajaxRequestFiles, partialRefresh } from "./functions.js";
import { handlePubPic } from "./index.js";

const userKey = getCookie("userKey");
let nombrePubs = 0;
let idPub;
let commentaireInterval = 0;
let nbComms = 0;

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
  let str = pub.nbComms === 1 ? "commentaire" : "commentaires";
  return `
  <div class="publication" id=${pub.idPublication}>
    <i class="fa-solid fa-ellipsis fa-xl more" idPub="${pub.idPublication
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
          Enregistrer &nbsp
        </i>
          <i class="fa-regular fa-bookmark"></i>
        </li>
        <li class="supprimer-pub">
        <i>
        Supprimer &nbsp
        </i>  
          <i class="fa-solid fa-xmark fa-lg"></i>
        </li>
      </ul>
    </div>
    <div class="infos-publication">
        <a href="" class="profil-pic">
            <div class="profil-pic-container" style="background-image: url(${pub.profilePic ? pub.profilePic : './default-profile-pic-.jpg'})">
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
    <div class="image-pub-container" style="background-image: url(./${pub.urlImage});">
    </div>
    <div class="likes-comment-container">
        <div class="nb-likes"  dir="auto">
            <i class="fa-solid fa-heart"></i>
            <span idPubLike=${pub.idPublication}>${pub.nbLikes}</span>
        </div>
        <div class="nb-comments" dir="auto">${pub.nbComms} ${str}</div>
    </div>
    <hr>
    <div class="actions-pub">
        <div class="aimer" idPub=${pub.idPublication}>
            aimer
            <i class="fa-regular fa-heart${pub.isLiked ? ' fa-solid' : ''}" id='c-heart' idPub=${pub.idPublication}></i>
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


function renderComments(comments) {
  comments.forEach((c) => {
    $('.comments').append(renderComment(c));
  });
}

function getPubById(id) {
  let pub;

  ajaxRequest("POST", "./server/get_pub_id.php", { id: id, userKey: userKey }, (p) => {
    pub = p;
  });
  return pub;
}

function renderComment(comment) {
  let res = getPubById(comment.idPublication);
  let pubUserId = res[0];
  let connectedUserId = res[1];
  let admin = pubUserId === connectedUserId;
  let myComment = comment.userKey === userKey;
  let supprimer = myComment || admin ? `<i title='supprimer' class="fa fa-times-circle del-icon" id=${comment.idComment} aria-hidden="true"></i>` : "";

  return `
    <div class="commentaire" idPub=${comment.idPublication} idCom=${comment.idComment}>
      <div class="img-profil"></div>
        <div class="comm-container">
          <div class="comm-name">${comment.firstName} ${comment.lastName}</div>
          <div class="comm-txt">${comment.content} ${supprimer}</div>
        </div>
    </div>
  `;
}

function forceRefreshPubs(id) {
  $("#refreshPubs").click(() => {
    requestComments(id);
  });
}

function get() {
  ajaxRequest("GET", "./server/get_publications_sse.php", { userKey: userKey, nbPubs: nombrePubs }, (data) => {
    let pubs = data[0];
    if (pubs.length > 0 && nombrePubs !== pubs.length) {
      nombrePubs = pubs.length;

      $(".publications-container .publication:not(:first), .publications-container footer").remove();

      pubs.forEach((pub) => {
        pub.isLiked = false;

        data[1].forEach((p) => {
          if (p.idPublication === pub.idPublication) {
            pub.isLiked = true;
          }
        });

        $(".publications-container").append(renderPublication(pub));
        $("i.more").off().on("click", function () {
          $(this).siblings(".dropdown-menu").toggleClass("show");
        });


        $(document).on("click", function (e) {
          if (!$(e.target).hasClass("comments") && !$(e.target).closest(".comments").length) {
            $(".comments.opened").removeClass("opened");
            $(".comments *:not('[contentEditable]')").remove();
            $(".publications-container").removeClass("no-scroll");
            $(".publication>*:not(.comments)").removeClass("blur");
          }
        });

        $(".commenter, .nb-comments").off().on("click", function (e) {
          e.stopPropagation();

          const id = $(e.target).parent().parent().attr('id'); // id de la publication choisie
          requestComments(id);
          // partialRefresh(false, null, null, commentaireInterval);
          // commentaireInterval = partialRefresh(true, () => requestComments(id) , 5_000, commentaireInterval);
          $(".publications-container").addClass("no-scroll");
          $(".publication>*:not(.comments)").addClass("blur");

          addCommentaire();
          deleteCommentaire(id);
          forceRefreshPubs(id);
          quit();

          if ($(this).hasClass("commenter")) {
            $("#comment").focus();
          }
        });

      });
      $(".publications-container").append("<footer style='height: 200px'></footer>");
    }
  });
}

function quit() {
  $(document).keyup(function (e) {
    if (e.key === "Escape") {
      $(document).click();
      // partialRefresh(false, null, null, commentaireInterval); 
    }
  });
}

function renderFormComment(idPub) {
  return `
       <div class="comment-form">
            <input type="text" id="comment" placeholder="Écrivez un commentaire...">
            <button id=${idPub} class="comment-btn">
            <i class="fa fa-paper-plane" aria-hidden="true"></i>
            </button>
        </div>
    `;
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
        if (data) {
          $("i.cancel").click();
          $("#thought").val("");
          get();
          likePublication();
        }
      });
    }
  });
}

function likePublication() {
  $(".aimer").on("click", (e) => {
    idPub = $(e.target).attr('idPub');
    ajaxRequest("POST", "./server/like_pub.php", { userKey: userKey, idPub: idPub }, (data) => {

      if (data) {
        $(`[idPubLike=${idPub}]`).text(data.nbLikes);

        $(`div.aimer [idpub=${idPub}]`).toggleClass("fa-solid");
      }
    });
  });
}

function deletePub() {
  $(".supprimer-pub").on("click", (e) => {
    idPub = e.target.closest(".publication").id;
    ajaxRequest("POST", "./server/delete_pub.php", { userKey: userKey, idPub: idPub }, (data) => {
      get();
    });
  });
}


//////////////////////
///////// COMMENTAIRES

function deleteCommentaire(idPub) {
  $(".del-icon").click(function (e) {
    const id = $(this).attr('id');
    const divCommentaire = $(this).parent().parent().parent();

    ajaxRequest("POST", "./server/delete_comment.php", { idComment: id, idPub: idPub }, (res) => {
      if (res) {
        $(divCommentaire).hide();
      }
    });

  });
}

function getCommentaire(pubId) {
  const commentaire = {};

  commentaire.content = $("#comment").val();
  commentaire.userKey = userKey;
  commentaire.idPublication = pubId;

  return commentaire;
}

function addCommentaire() {

  $(".comment-btn").click((e) => {

    const idPub = $(e.target).attr('id');
    const commentaire = getCommentaire(idPub);

    if (commentaire.content.trim().length !== 0) {
      ajaxRequest("POST", "./server/add_comment.php", { commentaire: commentaire }, (res) => {
        if (res) {
          $('.comments').append(renderComment(res));
          $("#comment").val('');
          deleteCommentaire(res.idPublication);
        }
      });
    } else {
      console.log('vide');
    }

  });
}

function requestComments(id) {
  let comments = [];
  ajaxRequest("POST", "./server/get_comments_pub.php", { idPub: id }, (res) => {
    if (res) {
      comments = res;
      viderContainer(".comments");
      $('.comments').append(renderFormComment(id));
      if (!$(".comments").hasClass("opened")) {
        $(".comments").append(renderComments(comments)).addClass("opened");
      }
    }
  });
}


$(document).ready(() => {
  getPublications();
  likePublication();
  handlePubPic();
  publish();
  deletePub();
});
