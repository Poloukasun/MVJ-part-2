import { getCookie, ajaxRequest, viderContainer, setSelected, partialRefresh } from "./functions.js";
const userKey = getCookie("userKey");

let usersAffiches = [];
let hasFriends = false;
let incrementLimit = true;
let limit = 3;
let thisId;

let intervalRequests = 0;
let intervalFriends = 0;

const actions = {
  "all-btn": get,
  "friends": getFriends,
  "see-requests-btn": getRequests,
};

function refreshAllData() {
  $('#refresh-all').on('click', function () {
    $(this).addClass('spin');

    getNbFriends();
    getNbRequests();
    if (thisId === undefined || thisId === 'all-btn') {
      actions['all-btn']();
    } else {
      actions[thisId]();
    }

    setTimeout(() => {
      $(this).removeClass('spin');
    }, 1000);
  });
}

const renderRequests = (requests) => {
  let element;

  $('#titre').text("Demandes d'amis");
  viderContainer(".container-users");

  requests.forEach((r) => {
    element = `
          <button class='bouton-ami' id='accept-btn' idFriendship='${r.idFriendship}' action='accept'>Accepter</button>
          <button class='refuser bouton-ami' id='reject-btn' idFriendship='${r.idFriendship}' action='reject'>Refuser</button>
      `;

    $('.container-users').append(renderUser(r, element));
  });

  $(".container-users").on("click", ".bouton-ami", (e) => {
    const action = $(e.target).attr('action');
    const idFriendship = $(e.target).attr('idFriendship');
    const divProfil = $(e.target).parent().parent();
    const data = {
      "idFriendship": idFriendship
    };
    let url;

    if (action === "reject") {
      url = "./server/reject_friendship.php";
    } else if (action === "accept") {
      url = "./server/accept_friendship.php";
    }

    ajaxRequest("POST", url, data, (response) => {
      if (response) {
        $(divProfil).remove();
        getNbRequests();
        getNbFriends();
      }
    });

  });
};

const renderFriends = (friends) => {

  let element;

  friends.forEach(f => {
    element = `<button class='refuser' id='delete-btn' idFriendship='${f.idFriendship}'>Retirer</button>`;
    $('.container-users').append(renderUser(f, element));
  });

  $('.container-users').on('click', '#delete-btn', (e) => {
    const divProfil = $(e.target).parent().parent();
    const idFriendship = $(e.target).attr('idFriendship');
    ajaxRequest("POST", "./server/reject_friendship.php", { "idFriendship": idFriendship }, (response) => {
      if (response) {
        $(divProfil).remove();
        getNbFriends();
      }
    });
  })
}

const renderUser = (user, element) => {
  return `
          <div class="profil" userKey='${user.userKey}'>
            <div style='background-image:url(${user.profilePic ?? "./profil-default.jpg"})' class="photo-profil">
            </div>
            <div class="nom">${user.firstName} ${user.lastName}</div>
            <div class='state'>
              ${element}
            </div>
          </div>
        `;
}

const renderUsers = (users, userKey) => {
  let friendships = [];
  let element;

  ajaxRequest("GET", "./server/get_friendships.php/", { userKey: userKey }, (data) => {
    friendships = data;
  });

  hasFriends = friendships.length > 0;

  users.forEach((u) => {
    if (hasFriends) {
      const areFriends = friendships.find((f) => {
        return (
          f.idUserAsking === u.connectedUserId &&
          f.idUserReceiver === u.idUser &&
          f.state === 1
        );
      });

      if (areFriends) {
        return;
      }

    }

    element = `<button id='add-btn' class="bouton-ami" userKey='${u.userKey}' action='add'>Ajouter</button>`;
    if (hasFriends) {
      friendships.forEach((f) => {
        if (
          f.idUserAsking === u.connectedUserId &&
          f.idUserReceiver === u.idUser
        ) {
          element = `<p>Demande envoyée</p>
                <button class='refuser bouton-ami' idFriendship='${f.idFriendship}' action='cancel'>Annuler</button> `;
        }
      });
    }
    usersAffiches.push(u);
    $(".container-users").append(renderUser(u, element));
  });

  $(".container-users").on("click", ".bouton-ami", (e) => {
    let idFriendship = $(e.target).attr("idFriendship");
    let targetedUserKey = $(e.target).parent().parent().attr("userKey");
    let stateDiv = $(e.target).parent();
    let data;
    let url;
    const action = $(e.target).attr("action");

    if (action === "add") {

      data = {
        connectedUserKey: userKey,
        targetedUserKey: targetedUserKey,
      };

      ajaxRequest("POST", "./server/create_friendship.php", data, (response) => {
        if (response) {
          viderContainer(stateDiv);
          $(stateDiv).append(`
                  <p>Demande envoyée</p>
                  <button class='refuser bouton-ami' action="cancel">Annuler</button>
              `);
        }
      }
      );
    } else if (action === "cancel") {
      if (idFriendship) {
        data = {
          idFriendship: idFriendship,
        };
        url = "./server/reject_friendship.php";
      } else {
        data = {
          connectedUserKey: userKey,
          targetUserKey: targetedUserKey,
        };
        url = "./server/cancel_invite.php";
      }
      ajaxRequest("POST", url, data, (response) => {
        if (response) {
          viderContainer(stateDiv);
          $(stateDiv).append(`
              <button id='add-btn' class="bouton-ami" userKey='${targetedUserKey}' action='add'>Ajouter</button>
          `);
        }
      });
    }
  });
};

function afficherMessage(longueur, message, classe) {
  $('.container-users').append(`<div class="no-friends"></div>`)
  if (longueur === 0) {
    console.log('affichage...');
    $('.no-friends').show();
    $('.no-friends').text(message);
  } else {
    $('.no-friends').hide();
  }
  $(`.${classe}`).text(`(${longueur})`);
}

function getRequests() {
  ajaxRequest("GET", "./server/get_requets_sse.php", { userKey: userKey }, (res) => {
    viderContainer(".container-users");
    if (res.length > 0 && thisId === "see-requests-btn") {
      renderRequests(res);
    } else {
      console.log(res.length);
      afficherMessage(res.length, "Vous n'avez aucune demande d'ami pour le moment", "nb-demandes");
    }
  });
}

function get(vider = true) {
  ajaxRequest("GET", "./server/get_users_sse.php", { userKey: userKey, limit: limit }, (data) => {
    if (data.length >= 0 && thisId === "all-btn" || thisId === undefined) {
      if (data.length !== limit) {
        incrementLimit = false;
      } else {
        incrementLimit = true;
      }
      if (vider)
        viderContainer(".container-users");
      renderUsers(data, userKey);
    }
  });
}

export function getFriends() {
  let friends = [];
  ajaxRequest("GET", "./server/get_friends.php/", { "userKey": userKey }, (data) => {
    if (data.length >= 0 && thisId === "friends") {
      friends = data;
      viderContainer(".container-users");
      renderFriends(friends);
      afficherMessage(friends.length, "Vous n'avez aucun ami pour le moment", "nb-amis");
    }
  });
  return friends;
}

function getNbRequests() {
  ajaxRequest("GET", "./server/get_nb_requests.php", { userKey: userKey }, (res) => {
    if (res) {
      const nbReq = res[0].nb_req;
      $(".nb-demandes").text(`(${nbReq})`);
    }
  });
}

function getNbFriends() {
  ajaxRequest("GET", "./server/get_nb_friend_sse.php", { userKey: userKey }, (res) => {
    if (res) {
      const nbFriends = res;
      $(".nb-amis").text(`(${nbFriends})`);
    }
  });
}

function handleUsersDisplay() {
  $("#all-btn, #friends, #see-requests-btn").click((e) => {

    thisId = $(e.target).attr('id');
    setSelected($(e.target));

    switch (thisId) {
      case "see-requests-btn":
        getRequests();
        partialRefresh(false, null, null, intervalFriends);
        intervalRequests = partialRefresh(true, getRequests, 10_000, intervalRequests);
        $('.refresh-container').show();
        break;
      case "friends":
        getFriends();
        partialRefresh(false, null, null, intervalRequests);
        intervalFriends = partialRefresh(true, getFriends, 10_000, intervalFriends);
        $('.refresh-container').show()
        break;
      case "all-btn":
        get();

        // suspensions des requetes d'affichage
        partialRefresh(false, null, null, intervalRequests);
        partialRefresh(false, null, null, intervalFriends);

        $('.refresh-container').hide();
        $('.no-friends').hide();
        break;
    }
  });
}

function showMoreOrLess() {
  $("#more").click(() => {
    if (incrementLimit) {
      limit += 3;
      get(true);
    }
  });

  $("#less").click(() => {
    if (limit > 3) {
      limit -= 3;
      get(true);
    }
  });
}

function forceRefresh() {
  $('#refreshButton').click(() => {
    if (thisId === 'friends') {
      getFriends();
    } else if (thisId === 'see-requests-btn') {
      getRequests();
    }
  });
}


$(document).ready(() => {
  $('.refresh-container').hide();
  $('.no-friends').hide();

  get();

  getNbRequests();
  getNbFriends();

  partialRefresh(true, getNbFriends, 5_000);
  partialRefresh(true, getNbRequests, 5_000);

  handleUsersDisplay();
  showMoreOrLess();
  forceRefresh();
  refreshAllData();
});
