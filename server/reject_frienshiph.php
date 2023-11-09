<?php 
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST)) {
            echo json_encode('works');
        }
    }

?>