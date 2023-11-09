<?php 
class User {
    private $firstName;
    private $lastName;
    private $birthDate;
    private $email;
    private $is_online;
    private $profil_picture;
    private $pass_hash;
    private $gender;
    private $confirmed;
    private $key;

    public function __construct($firstName, $lastName, $bithDate, $email, $is_online, $profil_picture, $gender, $pass_hash, $key) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthDate = $bithDate;
        $this->email = $email;
        $this->is_online = $is_online;
        $this->profil_picture = $profil_picture;
        $this->gender = $gender;
        $this->pass_hash = $pass_hash; 
        $this->confirmed = false;
        $this->key = $key;
    }

    public function get_first_name() {
        return $this->firstName;
    }

    public function get_last_name() {
        return $this->lastName;
    }

    public function get_is_online() {
        return $this->is_online;
    }

    public function get_email() {
        return $this->email;
    }

    public function get_profil_picture_url() {
        return $this->profil_picture;
    }

    public function get_password_hash() { return $this->pass_hash;}

    public function get_birth_date() { return $this->birthDate; }

    public function get_gender() { return $this->gender; }

    public function get_confirmed() { return $this->confirmed; }

    public function get_key() { return $this->key; }

}


?>