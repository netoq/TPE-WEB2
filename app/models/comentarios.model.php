<?php

class ModelComentarios{

    private $dbComentarios;

    function __construct(){
        $this->dbComentarios = new PDO('mysql:host=localhost;'.'dbname=db_productos;charset=utf8', 'root', '');
    }


    function getComentariosPorProducto($idProducto){
        $comentario =  $this->dbComentarios->prepare('SELECT comentario.titulo , comentario.texto, comentario.puntuacion FROM comentario INNER JOIN producto 
        ON producto.id = comentario.id_producto WHERE producto.id = ?');
        $comentario->execute([$idProducto]);
        return $comentario->fetchAll(PDO::FETCH_OBJ);
    }

    function DeleteComentarioDelModelo($id){
        $comentario = $this->dbComentarios->prepare('DELETE FROM comentario  WHERE id_comentario = ?');
        $comentario->execute([$id]);
        return $comentario->rowCount();// Trae un numero mayor a 0 si borro.
    }


    function insertarComentario($titulo,$texto,$puntuacion,$idUsuario,$idProducto){
        $comentario = $this->dbComentarios->prepare('INSERT INTO comentario (titulo,texto,puntuacion,id_usuario,id_producto) VALUES(?,?,?,?,?)');
        $comentario->execute([$titulo,$texto,$puntuacion,$idUsuario,$idProducto]);
        return $this->dbComentarios->lastInsertId();//Trae el ultimo id que toco.
    }
    function getComentarioId($idComentario){
        $comentario = $this->dbComentarios->prepare('SELECT * FROM comentario WHERE id_comentario = ?');
        $comentario->execute([$idComentario]);
        return $comentario->fetch(PDO::FETCH_OBJ);
    }

    function getComentario(){
        $comentario = $this->dbComentarios->prepare('SELECT * FROM comentario');
        $comentario->execute([]);
        return $comentario->fetchAll(PDO::FETCH_OBJ);
    }


}