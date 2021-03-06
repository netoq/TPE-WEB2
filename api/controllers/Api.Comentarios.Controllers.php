<?php
require_once 'app\models\productos.model.php';
require_once 'app\models\categorias.model.php';
require_once 'app\models\comentarios.model.php';
require_once 'api\view\Api.View.Producto.php';
require_once 'api\controllers\Api.Controllers.php';

class ApiComentariosController extends ApiControllers
{ //hereda e implementa todo de api controllers

    function __construct()
    {

        parent::__construct(); // con esto termina de implementar el apicontrollers
        $this->model = new ModelComentario();
        $this->view = new ApiViewProducto();
    }

    function showComentarioPorProducto($params = null)
    {
        $id = $params[':ID'];
        $comentarios = $this->model->getComentariosPorProducto($id);
        $this->view->response($comentarios, 200);
    }

    function deleteComentario($params = null)
    {
        $id = $params[':ID'];
        $comentario = $this->model->DeleteComentarioDelModelo($id);

        if ($comentario > 0)
            $this->view->response("El comentario con el id=$id fue eliminado", 200);
        else
            $this->view->response("El comentario con el id=$id no existe", 404);
    }

    function insertComentario($params = null)
    {
        $body = $this->getData();

        if (!empty($body->titulo) && !empty($body->texto) && !empty($body->puntuacion) && !empty($body->id_usuario) && !empty($body->id_producto)) {
            $titulo = $body->titulo;
            $texto = $body->texto;
            $puntucion = $body->puntuacion;
            $usuario = $body->id_usuario ;
            $id_producto =  $body->id_producto;

            $idComentario = $this->model->insertarComentario($titulo, $texto, $puntucion, $usuario, $id_producto);
            if (!empty($idComentario)) { // verifica si la comentario existe
                $this->view->response($this->model->getComentarioId($idComentario), 201);
            } else {
                $this->view->response("El comentario no se pudo insertar", 404);
            }
        }else{
            $this->view->response("El comentario no se pudo insertar", 404);
        }
    }

    function getComentario($params = null)
    {
        $id = $params[':ID'];
        $comentario = $this->model->getComentarioId($id);
        $this->view->response($comentario, 200);
    }
}
