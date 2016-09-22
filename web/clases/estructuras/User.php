<?php

namespace estructuras;

require_once 'Type.php';


/**
 * Usuario del sistema de centrovasco.com
 *
 * @author nicks
 */
class User {
    
    private $id;
    private $name;
    private $sname;
    private $mail;
    private $pass;
    private $type;
    
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }

    public function getSname() {
        return $this->sname;
    }

    public function getMail() {
        return $this->mail;
    }

    public function getPass() {
        return $this->pass;
    }

    public function getType() {
        return $this->type;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    public function setName($name) {
        $this->name = $name;
    }

    public function setSname($sname) {
        $this->sname = $sname;
    }

    public function setMail($mail) {
        $this->mail = $mail;
    }

    public function setPass($pass) {
        $this->pass = $pass;
    }

    public function setType(\estructuras\Type $type) {
        $this->type = $type;
    }

}
