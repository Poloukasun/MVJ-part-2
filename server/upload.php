<?php 
// ajax upload

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_FILES['image']['size'] > 0) { // fichier n'est pas vide
        echo json_encode("fichier n'est pas vide");   
    }
}
?>