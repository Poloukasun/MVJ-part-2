import {getCookie, ajaxRequest, log, viderContainer, partialRefresh, defilerBas} from "./functions.js";
const userKey = getCookie("userKey");
let friendKey='';
let messages = [];
let nbMessages = 0;
let lastId = 0;
let messagesInterval=0;
$(".confirm-box").hide();

$(document).ready(() => {

    const ytbPattern = /(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)))([^"&?\/\s]{11})/i;
    const imagePattern = /https?:\/\/(?:[a-zA-Z]|[0-9]|[$-_@.&+]|[!*'(),]|(?:%[0-9a-fA-F][0-9a-fA-F]))+\.(?:jpg|jpeg|gif|png|bmp|svg|webp)/gi;

    function elementExtractor(url) {

        let match;
        
        if(url.includes("youtube")) {
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

        if(isVideo) {
            return id ? 
            `<a style='text-decoration:none;' href="https://www.youtube.com/watch?v=${id}" target="_blank">
            <img style='border-radius:20px;' src="https://img.youtube.com/vi/${id}/0.jpg" alt="Video" width=400 height=250>
            </a>` : null;
        } 
        return id ? `<img style='border-radius:20px' src="${id}" alt="Image" width=400 height=250>` : null;
    }

    function scrollToBottom(element) {
        element.scrollTop = element.scrollHeight;
    }

    function renderFriend (f) {
        return `
            <div userKey=${f.userKey} class="user"><img src=../js/${f.profilePic ?? "./profil-default.jpg"}>${f.firstName}</div>
        `;
    }

    function chooseFriend() {
        $('.user').click((e) => {
            if(friendKey != $(e.target).attr('userKey')) {
                friendKey = $(e.target).attr('userKey');
                ajaxRequest("POST", "./server/change_friends.php", {friendKey:friendKey}, (friend) => {
                    viderContainer("#chatArea");
                    nbMessages=0;
                    $('#chatArea').append(getChatHeader(friend));
                    $("#chatArea").append(renderMessagesContainer());
                    $("#chatArea").append(getMessageInputArea());
                    getAllMessagesWith();
                    partialRefresh(false, null, null, messagesInterval);
                    messagesInterval = partialRefresh(true, getAllMessagesWith, 1000, messagesInterval);
                    eventsOfSending();  
                    let messagesContainer = document.querySelector("#chatMessages");
                    scrollToBottom(messagesContainer);
                });
            }
        });
    }

    function getChatHeader(friend) {
        return `
            <div id="chatHeader"><img src=../js/${friend.profilePic ?? "profil-default.jpg"}> ${friend.firstName} ${friend.lastName}</div>
        `;
    }

    function renderFriends(friends) {
        friends.forEach(f => {
            $("#userList").append(renderFriend(f));
        });

        chooseFriend();
    }

    function getMessageInputArea () {
        return `
        <div id="messageInputArea">
            <input id=message type="text" placeholder="Votre message...">
            <button id="send">Envoyer</button>
        </div> 
        `;
    }

    function fillFriendsList() {
        let friends = [];
        ajaxRequest("GET", "./server/get_friends.php", {userKey:userKey}, (res) => {
            friends = res;
        });
        
        if (friends.length>0) {
            renderFriends(friends);
        }
    }

    function showMessage(m) {
        if(m.connectedUserId === m.idSender) {
            $("#chatMessages").append(renderMessage("my-message", m, true));
            hoverMessage();
        } else if(m.connectedUserId !== m.idSender) {
            $("#chatMessages").append(renderMessage("f-message", m));
        }
    }

    function renderMessages(msg) {
        viderContainer("#chatMessages");
        $("#chatMessages").append('<button class=see-more-btn>Voir plus</button>');
        msg.forEach((m) => {
            showMessage(m)
        });
    }

    function renderMessagesContainer () {
        return `
            <div id=chatMessages>
            </div>
        `;
    }

    function renderMessage(classe, msg, currentUser=false) {
        const url = msg.type === 'V' || msg.type === 'P';
    
        const choicesDiv = currentUser ? `
            <div style='display:none' class='choices' id='choices${msg.idMessage}'>
                <i class="fa fa-pencil menu-button edit-button" aria-hidden="true" data-idmessage="${msg.idMessage}"></i>
                <i class="fa fa-trash menu-button delete-button" aria-hidden="true" data-idmessage="${msg.idMessage}"></i> 
            </div>
        ` : '';
    
        return `
            <div class="${classe} message" style=${url?"background-color:white":"background-color:#007bff"} id="message_${msg.idMessage}"> 
                ${msg.content}
                ${choicesDiv}
            </div>
        `;
    }

    function hoverMessage() {
        $(document).on('mouseover', '.message', function () {
            $(this).find('.choices').css('display', 'inline-block');
            handleEditDelete();
        }).on('mouseout', '.message', function () {
            $(this).find('.choices').css('display', 'none');
        });
    }

    function handleEditDelete() {
        $(document).off('click', '.delete-button').on('click', '.delete-button', function(e) {
            const id = $(this).data('idmessage');
            const messageDiv = $(`#message_${id}`);
            $(messageDiv).remove();
            ajaxRequest("POST", "./server/delete_message.php", {idMessage: id});
        });

        $(document).off('click', '.edit-button').on('click', '.edit-button', function() {
            const id = $(this).data('idmessage');
            const messagerieDiv = $(`#message_${id}`);
            const val = $(messagerieDiv).text().trim();
            console.log(val);
            messagerieDiv.hide();
            $('#chatMessages').append(renderInput(val, id));
            saveEdit();

        });
    }

    function saveEdit() {
        $(".save-btn").click(function (e) {
            const id = $(this).attr('id');
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
        message.type = 'M';
        return message;
    }

    function getAllMessagesWith() {
        

        ajaxRequest("POST", "./server/get_messages_with_friend.php", {userKey:userKey, friendKey:friendKey, last:nbMessages}, (res) => {
            nbMessages = res.length;
            let messagesContainer = document.querySelector("#chatMessages");
            if(res.length>0) {
                scrollToBottom(messagesContainer);
            } else {
                console.log("Vous n'avez aucun message... Démarrez la conversation!");
            }
            renderMessages(res);
        });
    }
    
    function send() {
        const message = newMessage();

        if (message.content.includes("https")) {
            message.content = getMsg(message.content);
            message.type = message.content.includes("youtube") ? "V" : "P";
        }

        ajaxRequest("POST", "./server/add_message.php", {message:message}, (msg) => {
            $('#chatMessages').append(renderMessage("my-message", msg));
            $('#message').val('');
        });    
    }

    function eventsOfSending() {
        $("#send").click(() => {
            send();
        });

        $("#message").keypress((e) => {
            if(e.key === 'Enter') 
                send();
        });
    }

    fillFriendsList();
});