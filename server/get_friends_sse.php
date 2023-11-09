<?php 
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
require_once("functions.php");
require_once("userDao.php");
require_once("friendshipsDao.php");
require_once("database.php");

$nombre_frienships = 0;
$attente = 1;

if(isset($_GET)) {

?>