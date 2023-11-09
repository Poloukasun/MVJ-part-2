<?php 

    function delete_session () {
        if(isset($_SESSION['userKey'])) {
            session_unset();
            session_destroy();
            return true;
        }
        return false;
    }
    
    function delete_cookie($value) {
        
        if(isset($_COOKIE[$value])) {
            unset($_COOKIE[$value]);
            return true;
        }
    
        return false;
        
    }
    
    function is_logged() {
        if(isset($_SESSION['userKey'])) {
            return true;
        }
        return false;
    }
    
?>