<?php

namespace estructuras;

/**
 * Comentario en el libro de visitas.
 *
 * @author nicks
 */
class Comment {
    
    private $id;
    private $nombre;
    private $comentario;
    private $mail;
    private $fecha;
    private $hora;
    private $revisado;
    
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getComentario() {
        return $this->comentario;
    }

    public function getMail() {
        return $this->mail;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getHora() {
        return $this->hora;
    }

    public function getRevisado() {
        return $this->revisado;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setComentario($comentario) {
        $this->comentario = $comentario;
    }

    public function setMail($mail) {
        $this->mail = $mail;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function setHora($hora) {
        $this->hora = $hora;
    }

    public function setRevisado($revisado) {
        $this->revisado = $revisado;
    }

}
