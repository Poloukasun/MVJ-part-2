<?php 
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
require_once("userDao.php");
require_once("functions.php");
require_once('messagesDao.php');

$wait = 1;
$nb_messages = 0;
$id_friend=0;
$friend=null;
$nb_messages=0;
if(isset($_GET)) {

    $connected_user = UserDao::get_user_by($_GET['userKey'], "userKey");
    $choosed_friend = UserDao::get_user_by($_GET['friendKey'], "userKey");

    if($connected_user && $choosed_friend) {
        $pdo = Database::connect();
            
        while(!connection_aborted()) {
            if($_GET['friendKey']){
                if($friend !== $choosed_friend){
                    $friend = $choosed_friend;
                }

                $messages = MessagesDao::get_messages_by_users($connected_user['idUser'], $friend['idUser'], $pdo);

                if($nb_messages !== sizeof($messages)) {
                    sent_sse($messages);
                    $nb_messages = sizeof($messages);
                }
            }
            sleep( $wait );
        }
    }
}
?>