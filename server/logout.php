<?php 
require_once("../sessions.php");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    delete_cookie("userKey");
    delete_session();
    exit;
}
