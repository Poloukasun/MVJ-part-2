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
            color: black;
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
            color: #007bff;
            transition: background 0.3s ease;
            cursor: pointer;
            font-size: 20px;
        }

        .save-btn:hover {
            color: #0056b3;
        }

        .checkbox-list {
            width: 300px;
            margin: auto;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            font-size: large;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: large;
        }

        .checkbox-item input[type="checkbox"] {
            margin-right: 10px;
            cursor: pointer;
        }

        .checkbox-item label {
            display: flex;
            align-items: center;
        }

        .checkbox-item label img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
            border-radius: 50%;
        }



        .buttons {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .buttons button {
            padding: 5px 15px;
            border: none;
            border-radius: 3px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        .buttons button:nth-child(2) {
            background-color: #f44336;
        }

        .buttons button:hover,
        .buttons button:focus {
            opacity: 0.9;
        }

        .create-group-btn {
            transition: transform 0.3s, box-shadow 0.3s;
            color: #007bff;
        }

        .create-group-btn:hover {
            color: #0056b3;
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        #groupName {
            width: 100%;
            margin-bottom: 15px;
            height: 35px;
            border-radius: 10px;
            border-color: #ddd;
            outline: none;
        }

        h3 {
            text-align: center;
        }

        .private {
            cursor: pointer;
        }

        .gr-creation {
            margin-bottom: 10px;
        }

        input[type='checkbox'] {
            cursor: pointer;
        }

        #friendsGroups {
            padding: 8px 12px;
            border: 2px solid #007bff;
            border-radius: 6px;
            box-shadow: 0px 4px 8px rgba(0, 123, 255, 0.2);
            background-color: white;
            font-family: 'Times New Roman', serif;
            font-size: 18px;
            outline: none;
            cursor: pointer;
        }

        #friendsGroups:hover {
            border-color: #0056b3;
        }
    </style>
</head>

<body>

    <div class="checkbox-list">

        <!-- Ajout dynamique -->
    </div>

    <div id="container">

        <div id="userList">
            <h3 style="text-align: center;">
                <select id="friendsGroups">
                    <option style="cursor: pointer;" value="f">Amis</option>
                    <option value="g">Groupes</option>
                </select>
                <i title="créer un groupe" id="create-group-btn" style="font-size: larger; float:right; cursor:pointer" class="fa fa-plus-square create-group-btn" aria-hidden="true"></i>
            </h3>
            <hr>    
               
        </div>
        
        <div id="chatArea">
            <div class="no-friend-choosed">
                <h3>Choisissez un ami pour partir une conversation</h3>
            </div>
        </div>
    </div>
</body>

</html>