import {getCookie, ajaxRequest, log, viderContainer, partialRefresh, defilerBas} from "./functions.js";
const userKey = getCookie("userKey");
let friendKey='';
let messages = [];
let nbMessages = 0;
let idMessage=0;
let lastId = 0;
let messagesInterval=0;
let todelete= false;
let edite = false;

$(document).ready(() => {

    function renderFriend (f) {
        return `
            <div userKey=${f.userKey} class="user"><img src=${f.profilePic ?? "./profil-default.jpg"}>${f.firstName}</div>
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
                    getTheLastMessage();
                    partialRefresh(false, null, null, messagesInterval);
                    messagesInterval = partialRefresh(true, getTheLastMessage, 1000, messagesInterval);
                    eventsOfSending();  
                    // hoverMessage();
                    $("#delete").click((e) => {
                        idMessage = $(e.target).attr('idMessage');
                        todelete=true;
                    });
                });
            }
        });
    }

    

    function getChatHeader(friend) {
        return `
                <div id="chatHeader"><img src=${friend.profilePic ?? "profil-default.jpg"}> ${friend.firstName} ${friend.lastName}</div>
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

    function showMessage(m, showMy=false) {
        
        if(m.connectedUserId === m.idSender && showMy) {
            $("#chatMessages").append(renderMessage("my-message", m));
        } else if(m.connectedUserId !== m.idSender) {
            $("#chatMessages").append(renderMessage("f-message", m));
        }
    }

    function renderMessages(msg) {
        if(Array.isArray(msg)) {
            viderContainer("#chatMessages");
            msg.forEach((m) => {
                showMessage(m, true)
            });
        } else {
            showMessage(msg);
        }
         
    }

    function renderMessagesContainer () {
        return `
            <div id=chatMessages></div>
        `;
    }

    function renderMessage (classe, msg) {
        return `
            <div class="${classe} message" id=${msg.idMessage}> 
                ${msg.content}
                <i class="fa fa-pencil menu-button" aria-hidden="true" id="edit"></i>
                <i class="fa fa-trash menu-button" aria-hidden="true" id="delete" ></i>
            </div>
        `;
    }

    function hoverMessage() {
        $('.message')
        .mouseover((e) => {
            const id = $(e.target).closest('.message').attr('id');
            
        })
        .mouseout((e) => {
            console.log('out');
            $('.container-menu').css('display', 'none');

        });
        
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
            nbMessages = messages.length;
            messages = res;
            if(messages.length>0) {
                lastId = messages[messages.length-1].idMessage;
                renderMessages(messages);
            } else {
                console.log("Vous n'avez aucun message... Démarrez la conversation!");
            }
        });
    }

    function getTheLastMessage() {
        ajaxRequest("POST", "./server/get_last_message.php", { userKey:userKey, friendKey:friendKey, lastId:lastId }, (lastMessage) => {
            console.log(lastMessage);
            lastId = lastMessage.idMessage;
            renderMessages(lastMessage);
        });
    }

    function send() {
        const message = newMessage();
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