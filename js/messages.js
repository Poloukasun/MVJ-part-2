import { getCookie, ajaxRequest, viderContainer, partialRefresh } from "./functions.js";

const userKey = getCookie("userKey");
let friendKey = "";
let nbMessages = null;
let isChatAdmin = false;
let messagesInterval = null;
let infoGroupRecu = null;
let precValSelect = null;
let inGroups = false;
let idGroup = null;

$(".checkbox-list").hide();

$(document).ready(() => {

  // chatgpt
  const ytbPattern = /(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)))([^"&?\/\s]{11})/i;
  const imagePattern = /https?:\/\/(?:[a-zA-Z]|[0-9]|[$-_@.&+]|[!*'(),]|(?:%[0-9a-fA-F][0-9a-fA-F]))+\.(?:jpg|jpeg|gif|png|bmp|svg|webp)/gi;
  // 

  function startPool(func) {
    messagesInterval = partialRefresh(true, func, 1000, messagesInterval);
  }

  function endPool() {
    partialRefresh(false, null, null, messagesInterval);
  }

  function elementExtractor(url) {
    let match;
    
    if (url.includes("youtube")) {
      match = url.match(ytbPattern);
      return [match ? match[1] : null, true];
    }

    match = url.match(imagePattern);
    return [match ? match[0] : null, false];
  }

  function getMsg(message) {
    let res = elementExtractor(message);
    let id = res[0];
    let isVideo = res[1];

    if (isVideo) {
      return id
        ? `<a style='text-decoration:none;' href="https://www.youtube.com/watch?v=${id}" target="_blank">
            <img style='border-radius:20px;' src="https://img.youtube.com/vi/${id}/0.jpg" alt="Video" width=400 height=250>
            </a>`
        : null;
    }
    return id ? `<img style='border-radius:20px' src="${id}" alt="Image" width=400 height=250>` : null;
  }

  function scrollToBottom(element) {
    element.scrollTop = element.scrollHeight;
  }

  function renderGroups(groups) {
    let div = document.createElement("div");

    for (const k in groups) {
      if (!Array.isArray(groups[k])) {
        
        $(div).append(
          `<img class='group' title="${groups[k].firstName + " " + groups[k].lastName}" style='border-radius:50%; width:40; height:40;' src='${groups[k].profilePic}'/>`
        );

        div.setAttribute("class", "group user");
        div.setAttribute("id", groups[k].idGroupChat);

      } else {
        div = document.createElement("div");
        div.setAttribute("class", "group user");
        div.setAttribute('id', groups[k][0].idGroupChat);

        for (const g of groups[k]) {
          $(div).append(
            `<img class='group' title="${g.firstName + " " + g.lastName}" style='border-radius:50%; width:40; height:40;' src='${g.profilePic}'/>`
          );
        }
        $(div).append(`<b>- ${groups[k][0].nom}</b>`);
      }
      $("#userList").append(div);
    }
  }

  function renderFriend(f, groupe = false) {
    if (!groupe) {
      return `
            <div userKey=${f.userKey} title="${f.firstName + " " + f.lastName}" class="user"><img src=${f.profilePic ?? "./profil-default.jpg"
        }> ${f.firstName} </div> `;
    } else {
      return `
        <div id='${groupe.idGroupChat}' class="user"><img src=${groupe.profilePic ?? "./profil-default.jpg"
        }>
            ${groupe.nom}
        </div>
      `;
    }
  }

  function setUpMessagesArea(e, isGroup = false) {
    $("#chatArea").append(getChatHeader(e, isGroup));
    
    if(isGroup) {
      handleQuitDeleteChat();
    }

    $("#chatArea").append(renderMessagesContainer());
    $("#chatArea").append(getMessageInputArea());
  }

  function chooseFriend() {
    $(".user").click((e) => {
      if (friendKey != $(e.target).attr("userKey")) {
        friendKey = $(e.target).attr("userKey");
        ajaxRequest("POST", "./server/change_friends.php", { friendKey: friendKey }, (friend) => {
          viderContainer("#chatArea");
          
          nbMessages = null;

          setUpMessagesArea(friend);
          getAllMessagesWith();

          endPool(getAllMessagesWith);
          startPool(getAllMessagesWith);

          eventsOfSending();

          let messagesContainer = document.querySelector("#chatMessages");
          scrollToBottom(messagesContainer);
        }
        );
      }
    });
  }

  function handleQuitDeleteChat() {
    $(".quit-chat").click(function () {
      const idGroup = $(this).attr('id');
      chatAreaReinitialiser();
      getMyGroups();
      chooseGroup();
      idGroup=null;
    });
  }

  function getChatHeader(friend, isGroup = false) {
    let symbole = '';

    if(isGroup) {
      console.log('dans if de isGroup')
      if(!isChatAdmin) {
        console.log('pas admin, donc peut quitter');
        symbole = `<i class="fa fa-sign-out group-btn-chat quit-chat" title='quitter le groupe' aria-hidden="true" id='${friend.id}'></i>`;
      } else {
        console.log('dans le else');
        symbole = `<i class="fa fa-trash delete-chat group-btn-chat" aria-hidden="true" id='${friend.id}'></i> `;
      }
    }


    const img = isGroup
      ? `<h3>${friend.nom}</h3>`
      : `
    <img src=${friend.profilePic ?? "profil-default.jpg"}> ${friend.firstName
      } ${friend.lastName}
    `;

    

    return `
          <div id="chatHeader" style='display:flex; justify-content:space-between;'>
            ${img}
            ${symbole}
          </div>
          `;
  }

  function renderFriends(friends) {
    friends.forEach((f) => {
      $("#userList").append(renderFriend(f));
    });
    chooseFriend();
  }

  function getMessageInputArea() {
    return `
    <div id="messageInputArea">
      <input id=message type="text" placeholder="Votre message...">
      <button id="send">Envoyer</button>
    </div>`;
  }

  function getFriends() {
    let friends = [];
    ajaxRequest("GET", "./server/get_friends.php", { userKey: userKey }, (res) => {
      friends = res;
    });
    return friends;
  }

  function userListFillHeader() {
    viderContainer("#userList");
    handleSelect();
  }

  function fillFriendsList() {
    userListFillHeader();
    let friends = getFriends();
    if (friends.length > 0) {
      renderFriends(friends);
    }
  }

  function chatAreaReinitialiser() {
    viderContainer("#chatArea");
    $("#chatArea").append(`
            <div class="no-friend-choosed">
            <h3>Choisissez un ami pour partir une conversation</h3>
      </div>
    `);
  }

  function handleSelect() {
    $("#friendsGroups").off().change(function () {
      let val = $(this).val();

      if (precValSelect !== val) {
        endPool();
        chatAreaReinitialiser();
        userListFillHeader();

        idGroup = null;
        nbMessages = null;

        if (val === "f") {
        
          fillFriendsList();
          inGroups = false;
        
        } else if (val === "g") {

          getMyGroups();
          chooseGroup();

          friendKey = null;
          inGroups = true;
        }
        precValSelect = val;
      }
    });
  }

  function getGroupsMessages() {
    ajaxRequest("POST", "./server/choose_group.php", { idGroup: idGroup, userKey: userKey, nbMessages: nbMessages, infoGroupRecu: infoGroupRecu }, (res) => {

      let group = null;
      let messages = null;

      if (Array.isArray(res)) {
        group = res[0];
        messages = res[1];
      } else {
        group = res;
      }

      // info recue
      infoGroupRecu = true;

      if (group.idAdmin === group.connectedUserId) {
        isChatAdmin = true;
      } else {
        isChatAdmin = false;
      }

      viderContainer("#chatArea");
      setUpMessagesArea(group, true);

      if (messages) {
        renderMessages(messages);
        nbMessages = messages.length;
      }
      eventsOfSending();
    });
  }

  function chooseGroup() {
    $(".group").click(function () {
      let lastId = idGroup;
      idGroup = parseInt($(this).attr("id"));
      console.log(idGroup);
      if (lastId != idGroup) {

        infoGroupRecu = false;
        nbMessages = null;

        endPool();
        getGroupsMessages();
        startPool(getGroupsMessages);
        console.log(isChatAdmin);

        lastId = idGroup;
      }

    });
  }

  function showMessage(m) {

    if (m.connectedUserId === m.idSender) {
      $("#chatMessages").append(renderMessage("my-message", m, "#007bff", true));
      hoverMessage();
    } else if (m.connectedUserId !== m.idSender) {
      $("#chatMessages").append(renderMessage("f-message", m));
    }

  }

  function renderMessages(msg) {
    viderContainer("#chatMessages");
    msg.forEach((m) => {
      showMessage(m);
    });
  }

  function renderMessagesContainer() {
    return `
            <div id=chatMessages>
            </div>
        `;
  }

  function deleteEditPermission(msg) {
    return `
        <i class="fa fa-trash menu-button delete-button" aria-hidden="true" data-idmessage="${msg.idMessage}"></i> 
    `;
  }

  function renderMessage(classe, msg, color = "black", currentUser = false) {

    const url = msg.type === "V" || msg.type === "P";
    const adminEtGroupe = inGroups && isChatAdmin;
    let choicesDiv = `<div style='display:none' class='choices' id='choices${msg.idMessage}'>`;

    if (adminEtGroupe) {
      if (currentUser) {
        choicesDiv += deleteEditPermission(msg);
      } 
    } else if (currentUser) {
      choicesDiv += deleteEditPermission(msg);
    }

    choicesDiv += `</div>`;

    return `
          <div class="${classe} message" style=${url ? "background-color:white" : "background-color:${color}"} id="message_${msg.idMessage}"> 
            ${msg.content}
            ${choicesDiv}
        </div>
    `;
  }

  function hoverMessage() {
    $(document)
      .on("mouseover", ".message", function () {
        $(this).find(".choices").css("display", "inline-block");
        handleEditDelete();
      })
      .on("mouseout", ".message", function () {
        $(this).find(".choices").css("display", "none");
      });
  }

  function handleEditDelete() {
    $(document).off("click", ".delete-button").on("click", ".delete-button", function () {

      const id = $(this).data("idmessage");
      const messageDiv = $(`#message_${id}`);

      ajaxRequest("POST", "./server/delete_message.php", { idMessage: id, inGroups: inGroups }, (res) => {
        if (res) {
          messageDiv.remove();
        }
      });
    });
  }

  function newMessage() {
    const message = {};
    message.content = $("#message").val();
    message.senderKey = userKey;
    if (!inGroups) {
      message.friendKey = friendKey;
    } else {
      message.idGroupe = idGroup;
    }
    message.type = "M";
    return message;
  }

  function getAllMessagesWith() {
    ajaxRequest(
      "POST",
      "./server/get_messages_with_friend.php",
      { userKey: userKey, friendKey: friendKey, last: nbMessages },
      (res) => {
        nbMessages = res.length;
        let messagesContainer = document.querySelector("#chatMessages");
        if (res.length > 0) {
          scrollToBottom(messagesContainer);
        } else {
          console.log("Vous n'avez aucun message... Démarrez la conversation!");
        }
        renderMessages(res);
      }
    );
  }

  function send() {
    const message = newMessage();

    if (message.content.includes("https")) {
      message.content = getMsg(message.content);
      message.type = message.content.includes("youtube") ? "V" : "P";
    }

    ajaxRequest("POST", "./server/add_message.php", { message: message, inGroups: inGroups }, (msg) => {
      $("#chatMessages").append(renderMessage("my-message", msg));
      $("#message").val("");
    });
  }

  function eventsOfSending() {
    $("#send").click(() => {
      send();
    });

    $("#message").keypress((e) => {
      if (e.key === "Enter") send();
    });
  }

  function renderCheckboxes() {
    let friends = getFriends();

    $(".checkbox-list").append(` 
      <div class='gr-creation'>
        <h3>Discutez à plusieurs !</h3>
        <input type="text" id='groupName' placeholder='Nom du groupe'> 
      </div>
    `);

    friends.forEach((f) => {
      console.log(f.profilePic);
      let friendDiv = $(`
          <div class="checkbox-item" id=${f.idUser}>
              <input class='check-friend' type="checkbox" id="${f.idUser}">
              <label for="${f.userKey}">
              <img src="${f.profilePic}" alt="${f.firstName}">
                  ${f.firstName} ${f.lastName}
              </label>
          </div>`);

      $(".checkbox-list").append(friendDiv);
    });

    $(".checkbox-list").append(`
            <div class="buttons">
                    <button id='add-friends-btn' type="button">Créer</button>
                    <button id='cancel-btn' type="button">Annuler</button>
            </div>
    `);

    $(".checkbox-list").show();
    addCancelCheckbox();
  }

  function getGroup() {
    const group = {
      nom: document.querySelector("#groupName").value
    };
    return group;
  }

  function removeElementByIdFromArray(liste, id) {
    for (const i in liste) {
      if (liste[i] === id) {
        liste.splice(i, 1);
        break;
      }
    }
    return liste;
  }

  function addCancelCheckbox() {
    const boutonAdd = $("#add-friends-btn");
    let lstKeys = [];

    $("#cancel-btn").click(() => {
      $(".checkbox-list").hide();
      viderContainer(".checkbox-list");
      $("#container").show();
      $(".choice").show();
    });

    $(".check-friend").change(function (e) {
      let id = $(this).attr("id");
      let isChecked = $(this).prop("checked");

      if (isChecked) {
        lstKeys.push(id);
      } else {
        removeElementByIdFromArray(lstKeys, id);
      }
    });


    boutonAdd.click(() => {
      const group = getGroup();
      group.lstKeys = lstKeys;

      ajaxRequest("POST", "./server/create_chat_group.php", { group: group, userKey: userKey });
    });
  }

  function createGroup() { // +
    $("#create-group-btn").click((e) => {
      $("#container").hide();
      $(".choice").hide();
      renderCheckboxes();
      endPool();
      chatAreaReinitialiser();
      friendKey = "";
    });
  }

  function getMyGroups() {
    let myGroups = [];

    ajaxRequest("POST", "./server/get_user_groups_chat.php", { userKey: userKey }, (res) => {
      if (res) {
        console.log(res);
        viderContainer("#userList");
        myGroups = res;
        renderGroups(myGroups);
      }
    }
    );

    return myGroups;
  }

  createGroup();
  fillFriendsList();
});
