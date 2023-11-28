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
      <!--
        <li>
          <i>
          Masquer &nbsp
          </i>
          <i class="fa-regular fa-eye-slash"></i>
        </li>
        -->
        <li class="Enregistrer" id="${pub.idPublication}">
          <i id="${pub.idPublication}">
            Enregistrer &nbsp
          </i>
          <i class="fa-regular fa-bookmark${pub.isRecording ? ' fa-solid' : ''}" idRecord="${pub.idPublication}" id="${pub.idPublication}"></i>
        </li>
        ${pub.isMine ? `<li class="supprimer-pub">
        <i>
        Supprimer &nbsp
        </i>  
        <i class="fa-solid fa-xmark fa-lg"></i>
      </li>` : ''}
      </ul>
    </div>
    <div class="infos-publication">
        <a href="./profil.php?${pub.userKey}" class="profil-pic">
            <div class="profil-pic-container" style="background-image: url(${pub.profilePic ? pub.profilePic : './default-profile-pic-.jpg'})">
            </div>
        </a>
        <div class="container-nom-date">
            <div class="profil-name" dir="auto">
                <a href="./profil.php?${pub.userKey}">
                  ${pub.groupName ? "<b>" + pub.groupName + "</b> - " : ""}
                  ${pub.firstName}
                  ${pub.lastName}
                </a>
            </div>
            <div class="date-publication" dir="auto">${formatRelativeDate(pub.publicationDate)}</div>
        </div>
        </div>
    <div class="titre" dir="auto">${pub.description}</div>
    ${pub.isImage == 1 ? `
    <div class="image-pub-container" style="background-image: url(${pub.urlImage});">
    ` : `
    <div class="image-pub-container video">
    <video width="90%" controls loop webkit-playsinline playsinline>
      <source src="${pub.urlImage}">
      Your browser does not support the video tag.
    </video>`}
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
        <!-- <div class="partager">
        partager
        <i class="fa-regular fa-share-from-square"></i>
    </div> -->
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
      <div class="img-profil" style="background-image: url(${comment.profilePic})"></div>
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

function search(pub, q) {
  const name = (pub.firstName + " " + pub.lastName).toLowerCase();
  const groupName = pub.groupName == null ? "" : pub.groupName.toLowerCase();
  return name.includes(q) || pub.description.toLowerCase().includes(q) || groupName.includes(q);
}

function get() {
  ajaxRequest("GET", "./server/get_publications_sse.php", { userKey: userKey, nbPubs: nombrePubs }, (data) => {
    let pubs = data[0];
    if (pubs.length > 0 && nombrePubs !== pubs.length) {
      nombrePubs = pubs.length;

      $(".publications-container .publication:not(:first), .publications-container footer").remove();

      const urlParams = new URLSearchParams(window.location.search);
      let searchReq = urlParams.get('q');
      let searchId = urlParams.get('id');

      $("#search").val(searchReq);

      $('input[type=search]').on('search', function (e) {
        if (e.target.value === "" && searchReq !== "") {
          $(".research-form").submit();
        }
      });

      if (searchReq) {
        pubs = pubs.filter((pub) => search(pub, searchReq.toLocaleLowerCase()));
      }

      if (searchId) {
        pubs = pubs.filter((pub) => pub.idPublication == searchId);
        $("#post-form").remove();
      }

      pubs.forEach((pub) => {
        pub.isLiked = false;
        pub.isRecording = false;

        data[2].forEach((p) => {
          if (p.idPub === pub.idPublication) {
            pub.isRecording = true;
          }
        });
        data[1].forEach((p) => {
          if (p.idPublication === pub.idPublication) {
            pub.isLiked = true;
          }
        });


        $(".publications-container").append(renderPublication(pub));
        let videos = document.querySelectorAll("video");
        videos.forEach((video) => {
          video.load();
        });
        $(".publications-container").scroll(function () {
          videos.forEach((video) => {
            if (!video.paused) {
              video.pause();
            }
          });
        });
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
          $('.comments').scrollTop(0);
        });

      });
      $(".publications-container").append("<footer style='height: 300px'></footer>");
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
            <i class="fa fa-paper-plane" id=${idPub} aria-hidden="true"></i>
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
    let file = $("#choose-image").prop("files")[0];
    let isImage = 1;
    if (!file) {
      file = $("#choose-video").prop("files")[0];
      isImage = 0;
    }
    const desc = $("#thought").val();

    if (file && desc.trim()) {
      let formData = new FormData();

      const idGroup = window.location.pathname.split('/').includes('feed-group.php') ? parseInt(new URLSearchParams(window.location.search)) : null;

      formData.append("file", file);
      formData.append("userKey", userKey);
      formData.append("desc", desc);
      formData.append("isImage", isImage)
      formData.append("idGroup", idGroup)

      ajaxRequestFiles(formData, "./server/add_publication.php", "POST", (data) => {
        if (data) {
          $("i.cancel").click();
          $("#thought").val("");
          get();
          likePublication();
          deletePub();
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
function Enregistrer() {
  $(".Enregistrer").on("click", (e) => {
    //let pub = e.target.closest(".publication");
    let pubEnregistrer = $(e.target).attr("id");
    ajaxRequest("POST", "./server/enregistrer_pub.php", { "userKey": userKey, "idPub": pubEnregistrer }, (data) => {
      if (data) {
        $(`[idRecord=${pubEnregistrer}]`).toggleClass("fa-solid");
      }
    });
  });
}
function deletePub() {
  $(".supprimer-pub").on("click", (e) => {
    let pub = e.target.closest(".publication");
    ajaxRequest("POST", "./server/delete_pub.php", { userKey: userKey, idPub: pub.id }, (data) => {
      pub.remove();
      get();
      deletePub();
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
        let nbComm = $('.publication[id="' + idPub + '"]').find('div.nb-comments');
        let text = nbComm.text().split(' ');
        nbComm.html((--text[0]) + " " + text[1]);
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
          $('#comment').val('');
          deleteCommentaire(res.idPublication);
          let nbComm = $('.publication[id="' + idPub + '"]').find('div.nb-comments');
          let text = nbComm.text().split(' ');
          nbComm.html((++text[0]) + " " + text[1]);
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
  Enregistrer();
});
