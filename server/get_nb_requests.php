<?php 
require_once("userDao.php");

if($_SERVER['REQUEST_METHOD']==='GET') {
    if(isset($_GET)) {
        $user_key = $_GET['userKey'];
        $user = UserDao::get_user_by($user_key, "userKey");
        $nb_req = UserDao::get_nb_requests($user);

        if($nb_req>0) {
            echo json_encode($nb_req);
        }
    } 
}

?>