<?php

namespace estructuras;

require_once 'Category.php';

use estructuras\Category;

/**
 * Noticia del sistema de centrovasco.com
 *
 * @author nicks
 */
class News {
    
    private $id;
    private $title;
    private $category;
    private $summary;
    private $body;
    private $pic_dir;
    private $author;
    private $datetime;
    private $important;
    
    public function getId() {
        return $this->id;
    }
    
    public function getTitle() {
        return $this->title;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getSummary() {
        return $this->summary;
    }

    public function getBody() {
        return $this->body;
    }

    public function getPic_dir() {
        return $this->pic_dir;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getDatetime() {
        return $this->datetime;
    }

    public function getImportant() {
        return $this->important;
    }
    
    public function setId($id) {
        $this->id = $id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setCategory(Category $category) {
        $this->category = $category;
    }

    public function setSummary($summary) {
        $this->summary = $summary;
    }

    public function setBody($body) {
        $this->body = $body;
    }

    public function setPic_dir($pic_dir) {
        $this->pic_dir = $pic_dir;
    }

    public function setAuthor(\estructuras\User $author) {
        $this->author = $author;
    }

    public function setDatetime($datetime) {
        $this->datetime = $datetime;
    }

    public function setImportant($important) {
        $this->important = $important;
    }

}
