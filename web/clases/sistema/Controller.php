<?php

namespace sistema;

require_once 'DataBase.php';
require_once 'clases/estructuras/Category.php';
require_once 'clases/estructuras/News.php';
require_once 'clases/estructuras/Type.php';
require_once 'clases/estructuras/User.php';
require_once 'clases/estructuras/Comment.php';

/**
 * Controlador del sistema de centrovasco.com
 *
 * @author nicks
 */
class Controller {
    
    private static $cantNews = 10;
    
    private static $cantImportantNews = 5;
    
    private static $cantComments = 10;
    
    private static $cantCategoryNews = 5;
    
    public static function getNews($page) {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT N.id, N.titulo, N.resumen, N.cuerpo, N.imagen, N.fecha, N.hora, C.id, C.nombre, C.descripcion, "
                . "U.id, U.nombre, U.apellido, U.mail, T.id, T.nombre, T.descripcion "
                . "FROM noticias N INNER JOIN usuarios U ON (N.autor=U.id) INNER JOIN categorias C ON (N.categoria=C.ID) "
                . "INNER JOIN tipos T ON (U.tipo=T.id) WHERE importante='0' ORDER BY N.fecha DESC, N.hora DESC LIMIT ?, ?"))) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $offset = ($page * self::$cantNews);
        if (!$query->bind_param("ii", $offset, self::$cantNews)) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $query->store_result();
        if (!$query->bind_result($id, $titulo, $resumen, $cuerpo, $imagen, $fecha, $hora,
                $cid, $cnommbre, $cdescripcion, $uid, $unombre, $uapellido, $umail, $tid, $tnombre, $tdescripcion)) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        
        $news = Array();
        
        while ($query->fetch()) {
            $c = new \estructuras\Category();
            $c->setDescription($cdescripcion);
            $c->setId($cid);
            $c->setName($cnommbre);
            
            $t = new \estructuras\Type();
            $t->setDescription($tdescripcion);
            $t->setId($tid);
            $t->setName($tnombre);
            
            $u = new \estructuras\User();
            $u->setId($uid);
            $u->setMail($umail);
            $u->setName($unombre);
            $u->setSname($uapellido);
            $u->setType($t);
            
            $n = new \estructuras\News();
            $n->setAuthor($u);
            $n->setBody($cuerpo);
            $n->setCategory($c);
            $n->setDatetime($fecha . ' ' . $hora);
            $n->setId($id);
            $n->setImportant(FALSE);
            $n->setPic_dir($imagen);
            $n->setSummary($resumen);
            $n->setTitle($titulo);
            
            $news[] = $n;
        }

        $query->close();
        $mysqli->close();
        
        return $news;
    }
    
    public static function getImportantNews($page) {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT N.id, N.titulo, N.resumen, N.cuerpo, N.imagen, N.fecha, N.hora, C.id, C.nombre, C.descripcion, "
                . "U.id, U.nombre, U.apellido, U.mail, T.id, T.nombre, T.descripcion "
                . "FROM noticias N INNER JOIN usuarios U ON (N.autor=U.id) INNER JOIN categorias C ON (N.categoria=C.ID) "
                . "INNER JOIN tipos T ON (U.tipo=T.id) WHERE importante='1' ORDER BY N.fecha DESC, N.hora DESC LIMIT ?, ?"))) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $offset = ($page * self::$cantImportantNews);
        if (!$query->bind_param("ii", $offset, self::$cantImportantNews)) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $query->store_result();
        if (!$query->bind_result($id, $titulo, $resumen, $cuerpo, $imagen, $fecha, $hora,
                $cid, $cnommbre, $cdescripcion, $uid, $unombre, $uapellido, $umail, $tid, $tnombre, $tdescripcion)) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        
        $news = Array();
        
        while ($query->fetch()) {
            $c = new \estructuras\Category();
            $c->setDescription($cdescripcion);
            $c->setId($cid);
            $c->setName($cnommbre);
            
            $t = new \estructuras\Type();
            $t->setDescription($tdescripcion);
            $t->setId($tid);
            $t->setName($tnombre);
            
            $u = new \estructuras\User();
            $u->setId($uid);
            $u->setMail($umail);
            $u->setName($unombre);
            $u->setSname($uapellido);
            $u->setType($t);
            
            $n = new \estructuras\News();
            $n->setAuthor($u);
            $n->setBody($cuerpo);
            $n->setCategory($c);
            $n->setDatetime($fecha . ' ' . $hora);
            $n->setId($id);
            $n->setImportant(TRUE);
            $n->setPic_dir($imagen);
            $n->setSummary($resumen);
            $n->setTitle($titulo);
            
            $news[] = $n;
        }

        $query->close();
        $mysqli->close();
        
        return $news;
    }
    
    public static function getAllNews() {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT N.id, N.titulo, N.resumen, N.cuerpo, N.imagen, N.fecha, N.hora, C.id, C.nombre, C.descripcion, "
                . "U.id, U.nombre, U.apellido, U.mail, T.id, T.nombre, T.descripcion, N.importante "
                . "FROM noticias N INNER JOIN usuarios U ON (N.autor=U.id) INNER JOIN categorias C ON (N.categoria=C.ID) "
                . "INNER JOIN tipos T ON (U.tipo=T.id) ORDER BY N.fecha DESC, N.hora DESC"))) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $query->store_result();
        if (!$query->bind_result($id, $titulo, $resumen, $cuerpo, $imagen, $fecha, $hora,
                $cid, $cnommbre, $cdescripcion, $uid, $unombre, $uapellido, $umail, $tid, $tnombre, $tdescripcion, $importante)) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        
        $news = Array();
        
        while ($query->fetch()) {
            $c = new \estructuras\Category();
            $c->setDescription($cdescripcion);
            $c->setId($cid);
            $c->setName($cnommbre);
            
            $t = new \estructuras\Type();
            $t->setDescription($tdescripcion);
            $t->setId($tid);
            $t->setName($tnombre);
            
            $u = new \estructuras\User();
            $u->setId($uid);
            $u->setMail($umail);
            $u->setName($unombre);
            $u->setSname($uapellido);
            $u->setType($t);
            
            $n = new \estructuras\News();
            $n->setAuthor($u);
            $n->setBody($cuerpo);
            $n->setCategory($c);
            $n->setDatetime($fecha . ' ' . $hora);
            $n->setId($id);
            $n->setImportant($importante=='1'? true: false);
            $n->setPic_dir($imagen);
            $n->setSummary($resumen);
            $n->setTitle($titulo);
            
            $news[] = $n;
        }

        $query->close();
        $mysqli->close();
        
        return $news;
    }
    
    public static function getSportsNews($page) {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT N.id, N.titulo, N.resumen, N.cuerpo, N.imagen, N.fecha, N.hora, C.id, C.nombre, C.descripcion, "
                . "U.id, U.nombre, U.apellido, U.mail, T.id, T.nombre, T.descripcion "
                . "FROM noticias N INNER JOIN usuarios U ON (N.autor=U.id) INNER JOIN categorias C ON (N.categoria=C.ID) "
                . "INNER JOIN tipos T ON (U.tipo=T.id) WHERE C.nombre='Deportes' ORDER BY N.fecha DESC, N.hora DESC LIMIT ?, ?"))) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $offset = ($page * self::$cantCategoryNews);
        if (!$query->bind_param("ii", $offset, self::$cantCategoryNews)) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $query->store_result();
        if (!$query->bind_result($id, $titulo, $resumen, $cuerpo, $imagen, $fecha, $hora,
                $cid, $cnommbre, $cdescripcion, $uid, $unombre, $uapellido, $umail, $tid, $tnombre, $tdescripcion)) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        
        $news = Array();
        
        while ($query->fetch()) {
            $c = new \estructuras\Category();
            $c->setDescription($cdescripcion);
            $c->setId($cid);
            $c->setName($cnommbre);
            
            $t = new \estructuras\Type();
            $t->setDescription($tdescripcion);
            $t->setId($tid);
            $t->setName($tnombre);
            
            $u = new \estructuras\User();
            $u->setId($uid);
            $u->setMail($umail);
            $u->setName($unombre);
            $u->setSname($uapellido);
            $u->setType($t);
            
            $n = new \estructuras\News();
            $n->setAuthor($u);
            $n->setBody($cuerpo);
            $n->setCategory($c);
            $n->setDatetime($fecha . ' ' . $hora);
            $n->setId($id);
            $n->setImportant(TRUE);
            $n->setPic_dir($imagen);
            $n->setSummary($resumen);
            $n->setTitle($titulo);
            
            $news[] = $n;
        }

        $query->close();
        $mysqli->close();
        
        return $news;
    }
    
    public static function getLanguageNews($page) {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT N.id, N.titulo, N.resumen, N.cuerpo, N.imagen, N.fecha, N.hora, C.id, C.nombre, C.descripcion, "
                . "U.id, U.nombre, U.apellido, U.mail, T.id, T.nombre, T.descripcion "
                . "FROM noticias N INNER JOIN usuarios U ON (N.autor=U.id) INNER JOIN categorias C ON (N.categoria=C.ID) "
                . "INNER JOIN tipos T ON (U.tipo=T.id) WHERE C.nombre='Idiomas' ORDER BY N.fecha DESC, N.hora DESC LIMIT ?, ?"))) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $offset = ($page * self::$cantCategoryNews);
        if (!$query->bind_param("ii", $offset, self::$cantCategoryNews)) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $query->store_result();
        if (!$query->bind_result($id, $titulo, $resumen, $cuerpo, $imagen, $fecha, $hora,
                $cid, $cnommbre, $cdescripcion, $uid, $unombre, $uapellido, $umail, $tid, $tnombre, $tdescripcion)) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        
        $news = Array();
        
        while ($query->fetch()) {
            $c = new \estructuras\Category();
            $c->setDescription($cdescripcion);
            $c->setId($cid);
            $c->setName($cnommbre);
            
            $t = new \estructuras\Type();
            $t->setDescription($tdescripcion);
            $t->setId($tid);
            $t->setName($tnombre);
            
            $u = new \estructuras\User();
            $u->setId($uid);
            $u->setMail($umail);
            $u->setName($unombre);
            $u->setSname($uapellido);
            $u->setType($t);
            
            $n = new \estructuras\News();
            $n->setAuthor($u);
            $n->setBody($cuerpo);
            $n->setCategory($c);
            $n->setDatetime($fecha . ' ' . $hora);
            $n->setId($id);
            $n->setImportant(TRUE);
            $n->setPic_dir($imagen);
            $n->setSummary($resumen);
            $n->setTitle($titulo);
            
            $news[] = $n;
        }

        $query->close();
        $mysqli->close();
        
        return $news;
    }
    
    public static function getSingNews($page) {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT N.id, N.titulo, N.resumen, N.cuerpo, N.imagen, N.fecha, N.hora, C.id, C.nombre, C.descripcion, "
                . "U.id, U.nombre, U.apellido, U.mail, T.id, T.nombre, T.descripcion "
                . "FROM noticias N INNER JOIN usuarios U ON (N.autor=U.id) INNER JOIN categorias C ON (N.categoria=C.ID) "
                . "INNER JOIN tipos T ON (U.tipo=T.id) WHERE C.nombre='Canto' ORDER BY N.fecha DESC, N.hora DESC LIMIT ?, ?"))) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $offset = ($page * self::$cantCategoryNews);
        if (!$query->bind_param("ii", $offset, self::$cantCategoryNews)) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $query->store_result();
        if (!$query->bind_result($id, $titulo, $resumen, $cuerpo, $imagen, $fecha, $hora,
                $cid, $cnommbre, $cdescripcion, $uid, $unombre, $uapellido, $umail, $tid, $tnombre, $tdescripcion)) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        
        $news = Array();
        
        while ($query->fetch()) {
            $c = new \estructuras\Category();
            $c->setDescription($cdescripcion);
            $c->setId($cid);
            $c->setName($cnommbre);
            
            $t = new \estructuras\Type();
            $t->setDescription($tdescripcion);
            $t->setId($tid);
            $t->setName($tnombre);
            
            $u = new \estructuras\User();
            $u->setId($uid);
            $u->setMail($umail);
            $u->setName($unombre);
            $u->setSname($uapellido);
            $u->setType($t);
            
            $n = new \estructuras\News();
            $n->setAuthor($u);
            $n->setBody($cuerpo);
            $n->setCategory($c);
            $n->setDatetime($fecha . ' ' . $hora);
            $n->setId($id);
            $n->setImportant(TRUE);
            $n->setPic_dir($imagen);
            $n->setSummary($resumen);
            $n->setTitle($titulo);
            
            $news[] = $n;
        }

        $query->close();
        $mysqli->close();
        
        return $news;
    }
    
    public static function getOtherNews($page) {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT N.id, N.titulo, N.resumen, N.cuerpo, N.imagen, N.fecha, N.hora, C.id, C.nombre, C.descripcion, "
                . "U.id, U.nombre, U.apellido, U.mail, T.id, T.nombre, T.descripcion "
                . "FROM noticias N INNER JOIN usuarios U ON (N.autor=U.id) INNER JOIN categorias C ON (N.categoria=C.ID) "
                . "INNER JOIN tipos T ON (U.tipo=T.id) WHERE C.nombre='Otros' ORDER BY N.fecha DESC, N.hora DESC LIMIT ?, ?"))) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $offset = ($page * self::$cantCategoryNews);
        if (!$query->bind_param("ii", $offset, self::$cantCategoryNews)) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $query->store_result();
        if (!$query->bind_result($id, $titulo, $resumen, $cuerpo, $imagen, $fecha, $hora,
                $cid, $cnommbre, $cdescripcion, $uid, $unombre, $uapellido, $umail, $tid, $tnombre, $tdescripcion)) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        
        $news = Array();
        
        while ($query->fetch()) {
            $c = new \estructuras\Category();
            $c->setDescription($cdescripcion);
            $c->setId($cid);
            $c->setName($cnommbre);
            
            $t = new \estructuras\Type();
            $t->setDescription($tdescripcion);
            $t->setId($tid);
            $t->setName($tnombre);
            
            $u = new \estructuras\User();
            $u->setId($uid);
            $u->setMail($umail);
            $u->setName($unombre);
            $u->setSname($uapellido);
            $u->setType($t);
            
            $n = new \estructuras\News();
            $n->setAuthor($u);
            $n->setBody($cuerpo);
            $n->setCategory($c);
            $n->setDatetime($fecha . ' ' . $hora);
            $n->setId($id);
            $n->setImportant(TRUE);
            $n->setPic_dir($imagen);
            $n->setSummary($resumen);
            $n->setTitle($titulo);
            
            $news[] = $n;
        }

        $query->close();
        $mysqli->close();
        
        return $news;
    }
    
    public static function getMaxPage() {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT COUNT(N.id) AS num FROM noticias N WHERE importante='0'"))) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $num = 0;
        if (!$query->bind_result($num)) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $query->fetch();
        
        $query->close();
        $mysqli->close();
        return floor(($num - 1) / self::$cantNews);
    }
        
    public static function getMaxSportsPage() {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT COUNT(N.id) AS num FROM noticias N INNER JOIN categorias C ON (N.categoria=C.id) WHERE C.nombre='Deportes'"))) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $num = 0;
        if (!$query->bind_result($num)) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $query->fetch();
        
        $query->close();
        $mysqli->close();
        return floor(($num - 1) / self::$cantCategoryNews);
    }
    
    public static function getMaxSingPage() {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT COUNT(N.id) AS num FROM noticias N INNER JOIN categorias C ON (N.categoria=C.id) WHERE C.nombre='Canto'"))) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $num = 0;
        if (!$query->bind_result($num)) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $query->fetch();
        
        $query->close();
        $mysqli->close();
        return floor(($num - 1) / self::$cantCategoryNews);
    }
    
    public static function getMaxLanguagePage() {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT COUNT(N.id) AS num FROM noticias N INNER JOIN categorias C ON (N.categoria=C.id) WHERE C.nombre='Idiomas'"))) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $num = 0;
        if (!$query->bind_result($num)) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $query->fetch();
        
        $query->close();
        $mysqli->close();
        return floor(($num - 1) / self::$cantCategoryNews);
    }
    
    public static function getMaxOtherPage() {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT COUNT(N.id) AS num FROM noticias N INNER JOIN categorias C ON (N.categoria=C.id) WHERE C.nombre='Otros'"))) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $num = 0;
        if (!$query->bind_result($num)) {
            die('ERROR AL PREPARAR LAS ULTIMAS NOVEDADES (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $query->fetch();
        
        $query->close();
        $mysqli->close();
        return floor(($num - 1) / self::$cantCategoryNews);
    }

    public static function login($mail, $hash) {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT U.id, U.nombre, U.apellido, U.mail, U.hash, T.id, T.nombre, T.descripcion FROM usuarios U "
                . "INNER JOIN tipos T ON (U.tipo=T.id) WHERE U.mail=? AND U.hash=password(?)"))) {
            die('ERROR AL CARGAR LA INFORMACION DE USUARIO (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->bind_param("ss", $mail, $hash)) {
            die('ERROR AL CARGAR LA INFORMACION DE USUARIO (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL CARGAR LA INFORMACION DE USUARIO (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->bind_result($uid, $nombre, $apellido, $email, $pass, $tipo_id, $tipo_nombre, $tipo_descripcion)) {
            die('ERROR AL CARGAR LA INFORMACION DE USUARIO (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        
        if ($query->fetch()) {
            $u = new \estructuras\User();
            $u->setId($uid);
            $u->setMail($email);
            $u->setName($nombre);
            $u->setPass($pass);
            $u->setSname($apellido);
            $t = new \estructuras\Type();
            $t->setDescription($tipo_descripcion);
            $t->setId($tipo_id);
            $t->setName($tipo_nombre);
            $u->setType($t);
            //session_start();
        } else {
            die('<html><head><meta charset="UTF-8"></head><body>ERROR AL CARGAR LA INFORMACION DE USUARIO <br />Usuario o contraseña inválidos (' . $mysqli->errno . '): ' . $mysqli->error) . '</body></html>';
        }

        $query->close();
        $mysqli->close();
        
        return $u;
    }
    
    public static function logout() {
        session_unset();
        session_destroy();
    }
    
    private static function updateImportantCount() {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("UPDATE noticias SET importante='0' WHERE id NOT IN ("
                    . "SELECT id FROM (SELECT id FROM noticias WHERE importante='1' ORDER BY fecha DESC, hora DESC LIMIT ?) FOO);"))) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->bind_param("i", self::$cantImportantNews)) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        
        $query->close();
        $mysqli->close();
    }
    
    public static function createNews(\estructuras\News $news) {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("INSERT INTO noticias (titulo, categoria, resumen, cuerpo, imagen, autor, fecha, hora, importante) "
                . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"))) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $importante = $news->getImportant()? '1': '0';
        if (!$query->bind_param("sisssisss", $news->getTitle(), $news->getCategory()->getId(), $news->getSummary(),
                $news->getBody(), $news->getPic_dir(), $news->getAuthor()->getId(), date("Y-m-d"), date("H:i:s"), $importante)) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        \sistema\Controller::updateImportantCount();
    }
    
    public static function modifyNews(\estructuras\News $news) {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("UPDATE noticias SET titulo=?, categoria=?, resumen=?, cuerpo=?, imagen=?, autor=?, fecha=?, hora=?, importante=? "
                . "WHERE id=?"))) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->bind_param("sisssisssi", $news->getTitle(), $news->getCategory()->getId(), $news->getSummary(),
                $news->getBody(), $news->getPic_dir(), $news->getAuthor()->getId(), date("Y-m-d"), date("H:i:s"),
                $news->getImportant(), $news->getId())) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        \sistema\Controller::updateImportantCount();
    }
    
    public static function deleteNews(\estructuras\News $news) {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("DELETE FROM noticias WHERE id=?"))) {
            die('ERROR AL ELIMINAR LA INFORMACION (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->bind_param("i", $news->getId())) {
            die('ERROR AL ELIMINAR LA INFORMACION (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL ELIMINAR LA INFORMACION (' . $mysqli->errno . '): ' . $mysqli->error);
        }
    }
    
    public static function fillNews(\estructuras\News $news) {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT N.titulo, N.resumen, N.cuerpo, N.imagen, N.fecha, N.hora, N.importante, "
                . "C.id, C.nombre, C.descripcion, "
                . "U.id, U.nombre, U.apellido, U.mail, U.hash, "
                . "T.id, T.nombre, T.descripcion "
                . "FROM noticias N INNER JOIN categorias C ON (N.categoria=C.id) INNER JOIN usuarios U ON (N.autor=U.id) "
                . "INNER JOIN tipos T ON (U.tipo=T.id) WHERE N.id=?"))) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->bind_param("i", $news->getId())) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $query->store_result();
        if (!$query->bind_result($titulo, $resumen, $cuerpo, $imagen, $fecha, $hora, $importante,
                $id_categoria, $nombre_categoria, $descripcion_categoria,
                $id_usuario, $nombre_usuario, $apellido_usuario, $mail_usuario, $hash_usuario,
                $id_tipo, $nombre_tipo, $descripcion_tipo)) {
            die('ERROR AL CARGAR LA INFORMACION DE USUARIO (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if ($query->fetch()) {
            $t = new \estructuras\Type();
            $t->setDescription($descripcion_tipo);
            $t->setId($id_tipo);
            $t->setName($nombre_tipo);
            
            $u = new \estructuras\User();
            $u->setId($id_usuario);
            $u->setMail($mail_usuario);
            $u->setName($nombre_usuario);
            $u->setPass($hash_usuario);
            $u->setSname($apellido_usuario);
            $u->setType($t);
            
            $c = new \estructuras\Category();
            $c->setDescription($descripcion_categoria);
            $c->setId($id_categoria);
            $c->setName($nombre_categoria);
            
            $news->setAuthor($u);
            $news->setBody($cuerpo);
            $news->setCategory($c);
            $news->setDatetime($fecha . ' ' . $hora);
            $news->setImportant($importante=='1'? true: false);
            $news->setPic_dir($imagen);
            $news->setSummary($resumen);
            $news->setTitle($titulo);
        } else {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }

        $query->close();
        $mysqli->close();
        
        return;
    }
    
    public static function createUser(\estructuras\User $user) {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("INSERT INTO usuarios (nombre, apellido, mail, hash, tipo) "
                . "VALUES (?, ?, ?, password(?), ?)"))) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->bind_param("ssssi", $user->getName(), $user->getSname(), $user->getMail(), $user->getPass(), $user->getType()->getId())) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
    }
    
    public static function modifyUser(\estructuras\User $user) {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("UPDATE usuarios U SET nombre=?, apellido=?, mail=?, hash=password(?), tipo=? "
                . "WHERE U.id=?"))) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->bind_param("ssssii", $user->getName(), $user->getSname(), $user->getMail(), $user->getPass(), $user->getType()->getId(), $user->getId())) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
    }
    
    public static function deleteUser(\estructuras\User $user) {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("DELETE FROM usuarios WHERE id=?"))) {
            die('ERROR AL ELIMINAR LA INFORMACION (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->bind_param("i", $user->getId())) {
            die('ERROR AL ELIMINAR LA INFORMACION (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL ELIMINAR LA INFORMACION (' . $mysqli->errno . '): ' . $mysqli->error);
        }
    }
    
    public static function listUsers() {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT U.id, U.nombre, U.apellido, U.mail, U.hash, T.id, T.nombre, T.descripcion FROM usuarios U INNER JOIN tipos T ON (U.tipo=T.id) ORDER BY U.nombre, U.apellido"))) {
            die('ERROR AL PREPARAR LOS USUARIOS (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL PREPARAR LOS USUARIOS (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $query->store_result();
        if (!$query->bind_result($id, $nombre, $apellido, $mail, $hash, $id_tipo, $nombre_tipo, $descripcion_tipo)) {
            die('ERROR AL PREPARAR LOS USUARIOS (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        
        $users = Array();
        
        while ($query->fetch()) {
            $u = new \estructuras\User();
            $u->setId($id);
            $u->setMail($mail);
            $u->setName($nombre);
            $u->setSname($apellido);
            $u->setPass($hash);
            $t = new \estructuras\Type();
            $t->setDescription($descripcion_tipo);
            $t->setId($id_tipo);
            $t->setName($nombre_tipo);
            $u->setType($t);
            
            $users[] = $u;
        }

        $query->close();
        $mysqli->close();
        
        return $users;
    }
    
    public static function postComment(\estructuras\Comment $comment) {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("INSERT INTO visitas (nombre, mail, comentario, fecha, hora, revisado) "
                . "VALUES (?, ?, ?, ?, ?, ?)"))) {
            die('ERROR AL CARGAR EL COMENTARIO (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $revisado = '0';
        $fecha = date("Y-m-d");
        $hora = date("H:i:s");
        if (!$query->bind_param("ssssss", $comment->getNombre(), $comment->getMail(), $comment->getComentario(), $fecha, $hora, $revisado)) {
            die('ERROR AL CARGAR EL COMENTARIO (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL CARGAR EL COMENTARIO (' . $mysqli->errno . '): ' . $mysqli->error);
        }
    }
    
    public static function getComments($page) {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT N.id, N.nombre, N.mail, N.comentario, N.fecha, N.hora FROM visitas N WHERE revisado='1' ORDER BY N.fecha DESC, N.hora DESC LIMIT ?, ?"))) {
            die('ERROR AL PREPARAR LOS ÚLTIMOS COMENTARIOS (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $offset = ($page * self::$cantComments);
        if (!$query->bind_param("ii", $offset, self::$cantComments)) {
            die('ERROR AL PREPARAR LOS ÚLTIMOS COMENTARIOS (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL PREPARAR LOS ÚLTIMOS COMENTARIOS (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $query->store_result();
        if (!$query->bind_result($id, $nombre, $mail, $comentario, $fecha, $hora)) {
            die('ERROR AL PREPARAR LOS ÚLTIMOS COMENTARIOS (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        
        $comments = Array();
        
        while ($query->fetch()) {
            $c = new \estructuras\Comment();
            $c->setComentario($comentario);
            $c->setFecha($fecha);
            $c->setHora($hora);
            $c->setId($id);
            $c->setMail($mail);
            $c->setNombre($nombre);
            $c->setRevisado(true);
            
            $comments[] = $c;
        }

        $query->close();
        $mysqli->close();
        
        return $comments;
    }
    
    public static function getMaxCommentPage() {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT COUNT(N.id) AS num FROM visitas N WHERE revisado='1'"))) {
            die('ERROR AL PREPARAR LOS ÚLTIMOS COMENTARIOS (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL PREPARAR LOS ÚLTIMOS COMENTARIOS (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $num = 0;
        if (!$query->bind_result($num)) {
            die('ERROR AL PREPARAR LOS ÚLTIMOS COMENTARIOS (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $query->fetch();
        
        $query->close();
        $mysqli->close();
        return floor(($num - 1) / self::$cantComments);
    }
    
    public static function getUncheckedComments() {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT N.id, N.nombre, N.mail, N.comentario, N.fecha, N.hora FROM visitas N WHERE revisado='0' ORDER BY N.fecha DESC, N.hora DESC"))) {
            die('ERROR AL PREPARAR LOS ÚLTIMOS COMENTARIOS (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL PREPARAR LOS ÚLTIMOS COMENTARIOS (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $query->store_result();
        if (!$query->bind_result($id, $nombre, $mail, $comentario, $fecha, $hora)) {
            die('ERROR AL PREPARAR LOS ÚLTIMOS COMENTARIOS (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        
        $comments = Array();
        
        while ($query->fetch()) {
            $c = new \estructuras\Comment();
            $c->setComentario($comentario);
            $c->setFecha($fecha);
            $c->setHora($hora);
            $c->setId($id);
            $c->setMail($mail);
            $c->setNombre($nombre);
            $c->setRevisado(false);
            
            $comments[] = $c;
        }

        $query->close();
        $mysqli->close();
        
        return $comments;
    }
    
    public static function getCheckedComments() {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("SELECT N.id, N.nombre, N.mail, N.comentario, N.fecha, N.hora, N.revisado FROM visitas N WHERE revisado='1' ORDER BY N.fecha DESC, N.hora DESC"))) {
            die('ERROR AL PREPARAR LOS ÚLTIMOS COMENTARIOS (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL PREPARAR LOS ÚLTIMOS COMENTARIOS (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        $query->store_result();
        if (!$query->bind_result($id, $nombre, $mail, $comentario, $fecha, $hora, $revisado)) {
            die('ERROR AL PREPARAR LOS ÚLTIMOS COMENTARIOS (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        
        $comments = Array();
        
        while ($query->fetch()) {
            $c = new \estructuras\Comment();
            $c->setComentario($comentario);
            $c->setFecha($fecha);
            $c->setHora($hora);
            $c->setId($id);
            $c->setMail($mail);
            $c->setNombre($nombre);
            $c->setRevisado($revisado == '1'? true: false);
            
            $comments[] = $c;
        }

        $query->close();
        $mysqli->close();
        
        return $comments;
    }
    
    public static function validateComment(\estructuras\Comment $comment) {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("UPDATE visitas U SET revisado='1' WHERE U.id=?"))) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->bind_param("i", $comment->getId())) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL CARGAR LA INFORMACION NUEVA (' . $mysqli->errno . '): ' . $mysqli->error);
        }
    }
    
    public static function deleteComment(\estructuras\Comment $comment) {
        $mysqli = new \mysqli(DataBase::getHost(), DataBase::getUser(), DataBase::getPass(), DataBase::getDbname(), DataBase::getPort());
        if ($mysqli->connect_errno) {
            die('ERROR AL ACCEDER A LA BASE DE DATOS DEL SISTEMA (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
        }
        if (!($query = $mysqli->prepare("DELETE FROM visitas WHERE id=?"))) {
            die('ERROR AL ELIMINAR LA INFORMACION (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->bind_param("i", $comment->getId())) {
            die('ERROR AL ELIMINAR LA INFORMACION (' . $mysqli->errno . '): ' . $mysqli->error);
        }
        if (!$query->execute()) {
            die('ERROR AL ELIMINAR LA INFORMACION (' . $mysqli->errno . '): ' . $mysqli->error);
        }
    }
    
}
