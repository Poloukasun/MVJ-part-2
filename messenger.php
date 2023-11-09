<?php 
require_once("header.php"); 
require_once("./sessions.php");
session_start();

if (!is_logged()) {
    header("Location:login_register.php");  
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script type="module" src="js/messages.js"></script>
    <title>Messenger</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            margin-top: 130px;
        }

        #container {
            display: flex;
            width: 80%;
            margin: 50px auto;
            box-shadow: 2px 2px 25px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
        }

        #userList {
            width: 25%;
            padding: 10px;
            overflow-y: auto;
            height: 500px;
            border-right: 1px solid #e9ecef;
        }

        .user {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
            transition: background 0.3s;
        }

        .user:hover {
            background-color: #f1f3f5;
        }

        .user img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            object-fit: cover;

        }

        #chatArea {
            width: 75%;
            padding: 0;
            position: relative;
        }

        #chatHeader {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e9ecef;
            padding: 10px;
            background-color: #f8f9fa;
        }

        #chatHeader img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        #chatMessages {
            padding: 10px;
            overflow-y: auto;
            height: 400px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            flex-direction: column;
        }

        #messageInputArea {
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8f9fa;
        }

        #messageInputArea input {
            padding: 8px;
            border: 1px solid #e9ecef;
            border-radius: 25px;
            outline: none;
            flex: 1;
            margin-right: 10px;
        }

        #messageInputArea button {
            padding: 8px 15px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .f-message {
            background-color: #e0e0e0;
            color: white;
            align-self: flex-start;
        }

        .my-message {
            background-color: #007bff;
            color: #ffffff;
            align-self: flex-end;
            text-align: right;
        }

        .message {
            max-width: 70%;
            padding: 8px 10px;
            margin-bottom: 8px;
            border-radius: 15px;
            text-align: left;
            word-wrap: break-word;
            position: relative;
            clear: both;
        }

        #messageInputArea button:hover {
            background-color: #0056b3;
        }

        .no-friend-choosed {
            display: flex;
            justify-content: center;
            margin-top: 20%;
        }

        .container-menu {
            display: none;
        }


        .menu-button {
            cursor: pointer;
            color:black;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .menu-button:hover { 
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .see-more-btn {
            background: none;
            cursor: pointer;
            border: none;
            color: blue;
            margin-bottom: 0;
        }

        .edit-container {
            text-align: right;
        }

        .new-content {
            width: 200px;
        }

        .save-btn {
            color:#007bff;
            transition: background 0.3s ease;
            cursor: pointer;
            font-size: 20px;
        }

        .save-btn:hover {
            color:#0056b3;
        }

        

    </style>
</head>

<body>

  

    <div id="container">

        <div id="userList">
            <h3 style="text-align: center;">Conversations</h3>
            <hr>
        </div>
        <div id="chatArea">
            <div class="no-friend-choosed">
                <h3>Choisissez un ami pour partir une conversation</h3>
            </div>
        </div>
    </div>

    <!-- <script>
        document.getElementById("show-menu").addEventListener("click", function() {
            var menu = document.getElementById("menu");
            if (menu.style.display === "none") {
                menu.style.display = "block";
            } else {
                menu.style.display = "none";
            }
        });

        document.getElementById("edit").addEventListener("click", function() {
            // Ajoutez ici le code à exécuter lorsque le bouton "Modifier" est cliqué.
        });

        document.getElementById("delete").addEventListener("click", function() {
            // Ajoutez ici le code à exécuter lorsque le bouton "Supprimer" est cliqué.
        });
      
    </script> -->
</body>

</html>