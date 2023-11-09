<?php 
use \PHPMailer\PHPMailer\PHPMailer;
require_once("../vendor/autoload.php");


function generate_unique_key() {
    $key = uniqid("",true);

    $key .= bin2hex(random_bytes(10)); 

    return $key;
}

function sent_sse ($data) {
    echo "data: " . json_encode($data) . "\n\n";
    ob_flush();
    flush();
}

function render_email_body($user) {
    $name = $user->get_first_name();
    $key = $user->get_key();
    return "
        <div style='margin: 10px; padding:20px; background-color:#f0f0f0; border:1px solid #ddd; border-radius: 5px; text-align: center; font-family:Arial, sans-serif'>

            <h2 style='font-size:20px; color: #333;'>Bonjour $name, nous sommes ravis de vous compter parmis nous! </h2><br>
            <p style='font-size:16px; color:#666;'> En cliquant sur le bouton ci dessous, votre compte sera confirmé et vous serrez dirigé vers
            la page de connexion. </p>
            <br><br>
            <button style='background-color:#007bff; color:#fff; padding:10px 20px; font-size:18px; border:none; border-radius:5px; cursor:pointer;'><a href='http://149.56.128.77/vladz/server/confirmation.php?key=$key'>Confirmer mon compte</a></button>

        </div>
    ";
}

function uniqueRandom($min, $max) {
    static $generatedNumbers = array();
    
    if(count($generatedNumbers) >= ($max - $min + 1)) {
        throw new Exception("Tous les nombres uniques dans cette plage ont été générés.");
    }
    
    do {
        $random = rand($min, $max);
    } while(in_array($random, $generatedNumbers));
    
    $generatedNumbers[] = $random;
    return $random;
}

function compare($str1, $str2) {
    return $str1 === $str2;
}

function send_mail($user, $subject) {
    
    $mail = new PHPMailer(TRUE);
    $mail->isHTML(true);

    $mail->setFrom('mvj.media@clg.qc.ca', 'Admin');
    
    $mail->addAddress($user->get_email(), $user->get_first_name());
    
    $mail->Subject = $subject;
    
    $mail->Body = render_email_body($user);
    
    $mail->CharSet = 'UTF-8';
       
    return $mail->send();
}
?>