<?php

require_once 'app\views\productos.view.php';
require_once 'app\models\categorias.model.php';
require_once 'app\models\admin.model.php';
require_once 'app\models\productos.model.php';
require_once 'app\controllers\admin.controllers.php';
require_once 'app\controllers\helper.php';
require_once 'app\models\comentarios.model.php';


class ProductosControllers
{

    private $modelProducto;
    private $modelCategoria;
    private $modelComentario;
    private $modelAdmin;
    private $view;
    private $helper;

    function __construct()
    {
        $this->modelProducto = new ModelProducto();
        $this->modelCategoria = new ModelCategoria();
        $this->modelComentario = new ModelComentario();
        $this->modelAdmin = new ModelAdmin();
        $this->helper = new helper();
        $this->user = $this->helper->checkConnection();
        $this->categorias = $this->modelCategoria->getAllCategorias();
        $this->view = new ViewProducto($this->categorias,$this->user);
    }


    /***********************  FUNCIONES DEL ADMINISTRADOR ***********************/


    //FUNCIONES DE LOS PRODUCTOS


    function showIndex()
    {
        $this->view->renderIndex($this->user);
    }

    function showProductosAdmin()
    {
        $user = $this->helper->checkLogin(); // SI hay una session iniciada me retorna el usuario registrado SINO me lleva al LOGOUT
        if ($user->permiso == 1) { //controlamos que el usuario en session tenga permiso
            $productos = $this->modelProducto->getAllProductos();
            $this->view->renderProductosAdmin($productos);
        } else {
            $this->view->ShowHomeLocationUsuario();
        }
    }

    function showCategoriasAdmin()
    {
        $user = $this->helper->checkLogin();
        if ($user->permiso == 1) {
            $this->view->renderCategoriasAdmin();
        } else {
            $this->view->ShowHomeLocationUsuario();
        }
    }


    function agregarProducto()
    {
        $user = $this->helper->checkLogin();
        if ($user->permiso == 1) {
            if (isset($_POST['input_title']) && isset($_POST['input_description']) && isset($_POST['input_precio']) && isset($_POST['select_categoria'])) {
                $nombre = $_POST['input_title'];
                $descripcion = $_POST['input_description'];
                $precio = $_POST['input_precio'];
                $categoria = $_POST['select_categoria'];
            }

            if ($_FILES['input_name']['type'] == "image/jpg" || $_FILES['input_name']['type'] == "image/jpeg" || $_FILES['input_name']['type'] == "image/png") {
                $this->modelProducto->insertarProductoImg($nombre, $descripcion, $precio, $categoria, $_FILES['input_name']['tmp_name']);
            } else {
                $this->modelProducto->insertarProductoImg($nombre, $descripcion, $precio, $categoria);
            }
            $this->view->ShowHomeLocation();
        } else {
            $this->view->ShowHomeLocationUsuario();
        }
    }

    function editarP($params = null)
    {
        $user = $this->helper->checkLogin();
        if ($user->permiso == 1) {
            $id = $params[':ID'];
            $producto = $this->modelProducto->getDetalleProducto($id);
            $this->view->renderFormEditar($id, $producto);
        } else {
            $this->view->ShowHomeLocationUsuario();
        }
    }

    function editarProducto($params = null)
    {
        $user = $this->helper->checkLogin();
        if ($user->permiso == 1) {
            $id = $params[':ID'];
            if (isset($_REQUEST['input_title']) && isset($_REQUEST['input_description']) && isset($_REQUEST['input_precio']) && isset($_REQUEST['select_categoria'])) {
                $nombre = $_REQUEST['input_title'];
                $descripcion = $_REQUEST['input_description'];
                $precio = $_REQUEST['input_precio'];
                $categoria = $_REQUEST['select_categoria'];
            }
            if ($_FILES['input_name']['type'] == "image/jpg" || $_FILES['input_name']['type'] == "image/jpeg" || $_FILES['input_name']['type'] == "image/png") {
                $this->modelProducto->editarProductoID($nombre, $descripcion, $precio, $categoria, $id, $_FILES['input_name']['tmp_name']);
            } else {
                $this->modelProducto->editarProductoID($nombre, $descripcion, $precio, $categoria, $id);
            }
            $this->view->ShowHomeLocation();
        } else {
            $this->view->ShowHomeLocationUsuario();
        }
    }

    function eliminarProducto($params = null)
    {
        $id = $params[':ID'];
        $user = $this->helper->checkLogin();
        if ($user->permiso == 1) {
            $this->modelProducto->eliminarProductoID($id);
            $this->view->ShowHomeLocation();
        }
    }

    function eliminarImagen($params = null)
    {
        $id = $params[':ID'];
        $user = $this->helper->checkLogin();
        if ($user->permiso == 1) {
            $this->modelProducto->eliminarImagenID($id);
            $this->view->ShowHomeLocation();
        }
    }

    //FUNCIONES DE LAS CATEGORIAS

    function agregarCategoria()
    {
        $user = $this->helper->checkLogin();
        if ($user->permiso == 1) {

            if (isset($_POST['input_title']) && isset($_POST['input_description']) && isset($_POST['input_origen'])) {
                $nombre = $_POST['input_title'];
                $descripcion = $_POST['input_description'];
                $origen = $_POST['input_origen'];
            }

            $this->modelCategoria->insertarCategoria($nombre, $descripcion, $origen);
            $this->view->ShowHomeLocationCategory();
        } else {
            $this->view->ShowHomeLocationUsuario();
        }
    }

    function eliminarCategoria($params = null)
    {
        $user = $this->helper->checkLogin();
        if ($user->permiso == 1) {
            $id = $params[':ID'];
            $categoriaID = $this->modelCategoria->categoriaID($id);

            if ($categoriaID === false) {
                $this->modelCategoria->eliminarCategoriaID($id);
                $this->view->ShowHomeLocationCategory();
            }
        } else {
            $this->view->ShowHomeLocationUsuario();
        }
    }

    function editarC($params = null)
    {
        $user = $this->helper->checkLogin();
        if ($user->permiso == 1) {
            $idCategoria = $params[':ID'];
            $categoria = $this->modelCategoria->getNombreCategoria($idCategoria);
            $this->view->renderFormEditarCategoria($idCategoria, $categoria);
        } else {
            $this->view->ShowHomeLocationUsuario();
        }
    }

    function editarCategoria($params = null)
    {
        $user = $this->helper->checkLogin();
        if ($user->permiso == 1) {
            $id = $params[':ID'];
            if (
                isset($_REQUEST['input_title']) && isset($_REQUEST['input_description'])
                && isset($_REQUEST['input_origen'])
            ) {
                $nombre = $_REQUEST['input_title'];
                $descripcion = $_REQUEST['input_description'];
                $origen = $_REQUEST['input_origen'];
            }

            $this->modelCategoria->editarCategoriaID($nombre, $descripcion, $origen, $id);
            $this->view->ShowHomeLocationCategory();
        } else {
            $this->view->ShowHomeLocationUsuario();
        }
    }
    
    function eliminarComentario($params = null)
    {
        $id = $params[':ID'];
        $user = $this->helper->checkLogin();
        if ($user->permiso == 1) {
            $this->modelComentario->DeleteComentarioDelModelo($id);
            $this->view-> ShowComentariosLocation();
        }
    }

    /********************** FUNCIONES DEL USUARIO **********************/

    function showProductos()
    {
        //1.obtener los productos   
        $productos = $this->modelProducto->getAllProductos();
        $this->view->renderProductos($productos, $this->user);
    }

    function showDetalleProducto($params = null)
    {
      
        $id = $params[':ID'];
        $producto = $this->modelProducto->getDetalleProducto($id);
        if (!isset($_SESSION['nombre'])) {
            $user = null;
        } else {
            $nombre = $_SESSION['nombre'];
            $user = $this->modelAdmin->getAdmin($nombre);
        }
        $this->view->renderDetalleProducto($producto, $user);
    }

    function showProductosByCategoria($params = null)
    {
        $idCategoria = $params[':ID'];
        $productosCategoria = $this->modelProducto->getProductosByCategoria($idCategoria);
        $nombreCategoriaId = $this->modelCategoria->getNombreCategoria($idCategoria);
        $this->view->renderProductosByCategoria($productosCategoria, $nombreCategoriaId);
    }

    function showCategoriasUsuario()
    {
        $this->view->renderCategoriasUsuario();
    }

    function showBusqueda()
    {
        $this->view->renderBusqueda();
    }

    function showBusquedaAvanzada($params = null)
    {
        if (
            isset($_POST['input_busquedaNombre']) && isset($_POST['input_busquedaCategoria'])
            && isset($_POST['input_busquedaDescripcion']) && isset($_POST['select_precioMin'])
            && isset($_POST['select_precioMax'])
        ) {
            $nombre = $_POST['input_busquedaNombre'];
            $categoria = $_POST['input_busquedaCategoria'];
            $descripcion = $_POST['input_busquedaDescripcion'];
            $precioMin = $_POST['select_precioMin'];
            $precioMax = $_POST['select_precioMax'];
        }
        if ($categoria == "") {
            $categoria = null;
            $productos = $this->modelProducto->getBusquedaAvanzada($nombre, $categoria, $descripcion, $precioMin, $precioMax);
        } else {
            $productos = $this->modelProducto->getBusquedaAvanzada($nombre, $categoria, $descripcion, $precioMin, $precioMax);
        }
        $this->view->ShowBusquedaLocationUsuario($productos);
    }


    function showComentario()
    {
        $user = $this->helper->checkLogin();
        if ($user->permiso == 1) {
            $comentarios = $this->modelComentario->getAllComentarios();
            $this->view->renderComentariosAdmin($comentarios);
        } else {
            $this->view->ShowHomeLocationUsuario();
        }
    }
}
