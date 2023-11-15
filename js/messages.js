import {
    getCookie,
    ajaxRequest,
    log,
    viderContainer,
    partialRefresh,
    defilerBas,
  } from "./functions.js";
  const userKey = getCookie("userKey");
  let friendKey = "";
  let nbMessages = 0;
  let messagesInterval = 0;
  let precValSelect = null;
  
  // $(".confirm-box").hide();
  $(".checkbox-list").hide();
  
  $(document).ready(() => {
    const ytbPattern =
      /(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)))([^"&?\/\s]{11})/i;
    const imagePattern =
      /https?:\/\/(?:[a-zA-Z]|[0-9]|[$-_@.&+]|[!*'(),]|(?:%[0-9a-fA-F][0-9a-fA-F]))+\.(?:jpg|jpeg|gif|png|bmp|svg|webp)/gi;
  
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
      return id
        ? `<img style='border-radius:20px' src="${id}" alt="Image" width=400 height=250>`
        : null;
    }
  
    function scrollToBottom(element) {
      element.scrollTop = element.scrollHeight;
    }
  
    function renderGroups(groups) {
      for(const k in groups) {
        // groups[k] = tableau de usager dans chaque groupe
        for(const g of groups[k]) {
          $('#userList').append(`<img class='group' style='border-radius:50%; width:40; height:40;' src='../jeanseb/${g.profilePic}'  />`);
        }
        $("#userList").append("<hr>");
      }
    }
  
    function renderGroup(g) {
      
    }
  
  
    function renderFriend(f, groupe=false) {
      if(!groupe) {
        return `
              <div userKey=${f.userKey} class="user"><img src=../jeanseb/${
                f.profilePic ?? "./profil-default.jpg"}>
                ${f.firstName}
              </div>
            `;
      } else {
        return `
          <div id='${groupe.idGroupChat}' class="user"><img src=../jeanseb/${
              groupe.profilePic ?? "./profil-default.jpg"}>
              ${groupe.nom}
          </div>
        `
      }
      
    }
  
    function chooseFriend() {
      $(".user").click((e) => {
        if (friendKey != $(e.target).attr("userKey")) {
          friendKey = $(e.target).attr("userKey");
          ajaxRequest(
            "POST",
            "./server/change_friends.php",
            { friendKey: friendKey },
            (friend) => {
              viderContainer("#chatArea");
              nbMessages = 0;
              $("#chatArea").append(getChatHeader(friend));
              $("#chatArea").append(renderMessagesContainer());
              $("#chatArea").append(getMessageInputArea());
              getAllMessagesWith();
              partialRefresh(false, null, null, messagesInterval);
              messagesInterval = partialRefresh(true, getAllMessagesWith, 1000, messagesInterval);
              eventsOfSending();
              let messagesContainer = document.querySelector("#chatMessages");
              scrollToBottom(messagesContainer);
            }
          );
        }
      }); 
    }
  
    function getChatHeader(friend) {
      return `
              <div id="chatHeader">
                  <img src=../jeanseb/${friend.profilePic ?? "profil-default.jpg"}> ${friend.firstName} ${friend.lastName} 
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
          </div> 
          `;
    }
  
    function getFriends() {
      let friends = [];
      ajaxRequest(
        "GET",
        "./server/get_friends.php",
        { userKey: userKey },
        (res) => {
          friends = res;
        }
      );
      return friends;
    }
  
    function userListFillHeader() {
      viderContainer('.userList');
  
      $('.userList').append(`
          <h3 style="text-align: center;">
          <select id="friendsGroups">
              <option style="cursor: pointer;" value="f">Amis</option>
              <option value="g">Groupes</option>
          </select>
          <i title="créer un groupe" id="create-group-btn" style="font-size: larger; float:right; cursor:pointer" class="fa fa-plus-square create-group-btn" aria-hidden="true"></i>
          </h3>
          <hr>
      `);
      handleSelect();
    }
  
    function fillFriendsList() {
      userListFillHeader();
      let friends = getFriends();
      if (friends.length > 0) {
        renderFriends(friends);
      }
    }
  
    function cacherElements(arr) {
      Array.from($(arr)).forEach(u=>{
        $(u).hide();
      })
    }
  
    function handleSelect() {
      
      $("#friendsGroups").change(function () {
        let val = $(this).val();
  
        if(precValSelect !== val) {
          if(val === 'f') {
            cacherElements(".group");
            fillFriendsList();
          } else if(val === 'g') {
            cacherElements(".user");
            getMyGroups();
          }
        }
      });
    }
  
    function showMessage(m) {
      if (m.connectedUserId === m.idSender) {
        $("#chatMessages").append(renderMessage("my-message", m, true));
        hoverMessage();
      } else if (m.connectedUserId !== m.idSender) {
        $("#chatMessages").append(renderMessage("f-message", m));
      }
    }
  
    function renderMessages(msg) {
      viderContainer("#chatMessages");
      $("#chatMessages").append("<button class=see-more-btn>Voir plus</button>");
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
  
    function renderMessage(classe, msg, currentUser = false) {
      const url = msg.type === "V" || msg.type === "P";
  
      const choicesDiv = currentUser
        ? `
              <div style='display:none' class='choices' id='choices${msg.idMessage}'>
                  <i class="fa fa-pencil menu-button edit-button" aria-hidden="true" data-idmessage="${msg.idMessage}"></i>
                  <i class="fa fa-trash menu-button delete-button" aria-hidden="true" data-idmessage="${msg.idMessage}"></i> 
              </div>
          `
        : "";
  
      return `
              <div class="${classe} message" style=${
        url ? "background-color:white" : "background-color:#007bff"
      } id="message_${msg.idMessage}"> 
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
      $(document)
        .off("click", ".delete-button")
        .on("click", ".delete-button", function (e) {
          const id = $(this).data("idmessage");
          const messageDiv = $(`#message_${id}`);
          $(messageDiv).remove();
          ajaxRequest("POST", "./server/delete_message.php", { idMessage: id });
        });
  
      $(document)
        .off("click", ".edit-button")
        .on("click", ".edit-button", function () {
          const id = $(this).data("idmessage");
          const messagerieDiv = $(`#message_${id}`);
          const val = $(messagerieDiv).text().trim();
          messagerieDiv.hide();
          $("#chatMessages").append(renderInput(val, id));
          saveEdit();
        });
    }
  
    function saveEdit() {
      $(".save-btn").click(function (e) {
        const id = $(this).attr("id");
        // ajaxRequest("POST", "./server/edit_msg.php", {idmsg:id, userKey:userKey, friendKey:friendKey, })
      });
    }
  
    function renderInput(value, id) {
      return `
          <div class='edit-container' >
              <input class='new-content message' type='text' id='newContent' idmessage='${id}' value='${value}'>
              <i title='enregistrer' id='${id}' class="fa fa-check save-btn" aria-hidden="true"></i>
          </div>
          `;
    }
  
    function newMessage() {
      const message = {};
      message.content = $("#message").val();
      message.senderKey = userKey;
      message.friendKey = friendKey;
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
  
      ajaxRequest(
        "POST",
        "./server/add_message.php",
        { message: message },
        (msg) => {
          $("#chatMessages").append(renderMessage("my-message", msg));
          $("#message").val("");
        }
      );
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
  
      $('.checkbox-list').append(` 
        <div class='gr-creation'>
          <h3>Discutez à plusieurs !</h3>
          <input type="text" id='groupName' placeholder='Nom du groupe'> 
          <label title='cocher pour rendre privé' class='private'><input id='priveCheck' type='checkbox'> <i class="fa fa-lock" aria-hidden="true"></i></label>
        </div>
      `);
  
  
      friends.forEach((f) => {
          let friendDiv = $(`
            <div class="checkbox-item" id=${f.idUser}>
                <input class='check-friend' type="checkbox" id="${f.idUser}">
                <label for="${f.userKey}">
                <img src="../jeanseb/${f.profilePic}" alt="${f.firstName}">
                    ${f.firstName} ${f.lastName}
                </label>
            </div>`
          );
  
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
  
    function changeBtnState(checked, boutonAdd) {
      let color = "#90EE90";
  
      if (!checked) {
        boutonAdd.prop("disabled", true);
      } else {
        boutonAdd.prop("disabled", false);
        color = "#4CAF50";
      }
  
      boutonAdd.css("background-color", color);
    }
  
    function getGroup () {
      const group = {
        isPrivate : document.querySelector("#priveCheck").checked ? 1 : 0,
        nom : document.querySelector("#groupName").value
      };
  
      return group;
    }
  
    function addCancelCheckbox() {
      const boutonAdd = $("#add-friends-btn");
      let lstKeys = [];
      changeBtnState(false, boutonAdd);
  
      $("#cancel-btn").click(() => {
        $(".checkbox-list").hide();
        viderContainer(".checkbox-list");
        $("#container").show();
      });
  
      $(".check-friend").change(function (e) {
  
        if ($(this).prop("checked")) {
          changeBtnState(true, boutonAdd);
          lstKeys.push($(this).attr("id"));
        } else {
          changeBtnState(false, boutonAdd);
        }
     
      });
  
      boutonAdd.click(() => {
        if (!boutonAdd.prop("disabled")) {
          const group = getGroup();
          group.lstKeys = lstKeys;
  
          ajaxRequest("POST", "./server/create_chat_group.php", { group:group, userKey:userKey }, (response) => {
              console.log(response);
            }
          );
        }
      });
    }
  
    function createGroup() {
      $("#create-group-btn").click((e) => {
        $("#container").hide();
        renderCheckboxes();
        partialRefresh(false,null,null,messagesInterval);
      });
    }
  
    function getMyGroups() {
      let myGroups = [];
  
      ajaxRequest("POST", "./server/get_user_groups_chat.php", {userKey:userKey}, (res) => {
        if(res) {
          myGroups=res;
  
          console.log(myGroups);
          renderGroups(myGroups);
        }
      });
      
      return myGroups;
    }
  
    
    createGroup();
    fillFriendsList();
    // getMyGroups();
  
  });
  