<?php

require_once 'app\views\admin.view.php';
require_once 'app\models\admin.model.php';
require_once 'app\models\categorias.model.php';
require_once 'app\controllers\helper.php';


class UsersControllers
{

    private $viewAdmin;
    private $modelAdmin;
    private $modelCategoria;
    private $helper;


    function __construct()
    {

        $this->modelCategoria = new ModelCategoria();
        $this->categorias = $this->modelCategoria->getAllCategorias();
        $this->viewAdmin = new ViewAdmin($this->categorias);
        $this->modelAdmin = new ModelAdmin();
        $this->helper = new helper();
    }

    function login()
    {
        session_start();
        if (isset($_SESSION["nombre"])) {
            $nombre = $_SESSION["nombre"];
            $user = $this->modelAdmin->getAdmin($nombre);
            if ($user->permiso == 1) {

                $this->viewAdmin->renderAdmin();
                die();
            } else {
                $mensaje = "NO TENES PERMISO DE ADMINISTRADOR";
                $this->viewAdmin->renderViewAdmin($mensaje);
            }
        } else {
            $this->viewAdmin->renderViewAdmin();
        }
    }

    function logout()
    {
        session_start();
        session_destroy();
        header("Location: " . LOGIN);
    }

    function verificarAdmin()
    {
        if (isset($_POST['input_admin']) && isset($_POST['input_passwordAdmin'])) {
            $nombre = $_POST['input_admin'];
            $password = $_POST['input_passwordAdmin'];
        }

        $adminDB =  $this->modelAdmin->getAdmin($nombre);

        if (isset($adminDB) && $adminDB) {

            if (password_verify($password, $adminDB->password_administrador)) {

                session_start();
                $_SESSION["nombre"] = $adminDB->nombre_administrador;
                $_SESSION['LAST_ACTIVITY'] = time();
                $nombre = $_SESSION["nombre"];
                $user = $this->modelAdmin->getAdmin($nombre);
                if ($user->permiso == 1) {
                    $this->viewAdmin->renderAdmin();
                } else {
                    $this->viewAdmin->renderIndex();
                }
            } else {
                $mensaje = "PASSWORD INCORRECTO";
                $this->viewAdmin->renderViewAdmin($mensaje);
            }
        } else {
            $mensaje = "NO EXISTE EL USUARIO";
            $this->viewAdmin->renderViewAdmin($mensaje);
        }
    }

    /********************** REGISTRO **********************/

    function showRegistro()
    {
        $this->viewAdmin->renderRegistro();
    }

    function agregarUsuario()
    {
        if (
            isset($_POST['input_nameRegister']) && isset($_POST['input_emailRegister'])
            && isset($_POST['input_passwordRegister']) && isset($_POST['input_confirmPassword'])
        ) {
            $nombre = $_POST['input_nameRegister'];
            $email = $_POST['input_emailRegister'];
            $password = $_POST['input_passwordRegister'];
            $confirm = $_POST['input_confirmPassword'];
        }
        $passEncrypt = password_hash($password, PASSWORD_DEFAULT); //encriptamos el password ingresado
        
        if ($password === $confirm) {
            if ($_FILES['input_name']['type'] == "image/jpg" || $_FILES['input_name']['type'] == "image/jpeg" || $_FILES['input_name']['type'] == "image/png") {       
                $this->modelAdmin->insertUser($nombre, $passEncrypt, $email, $_FILES['input_name']['tmp_name']);
            } else {
                $this->modelAdmin->insertUser($nombre, $passEncrypt, $email);
            }
            $this->iniciarSesionAuto($nombre);
        } else {
                $mensaje = 'Las contraseñas no coinciden';
                $this->viewAdmin->renderRegistro($mensaje);
        }
    }

    function iniciarSesionAuto($nombre)
    {
        $userDB =  $this->modelAdmin->getAdmin($nombre);
        if (isset($userDB) && $userDB) {
            session_start();
            $_SESSION["nombre"] = $userDB->nombre_administrador;
            $_SESSION['LAST_ACTIVITY'] = time();
            $this->viewAdmin->renderIndex();
        }
    }

    /******************* PERMISOS DEL USUARIO *********************/

    function showUsersAdmin()
    {
        $user = $this->helper->checkLogin();
        if ($user->permiso == 1) {
            $users = $this->modelAdmin->getAllUsers();
            $this->viewAdmin->renderUsersAdmin($users);
        } else {
            $this->viewAdmin->renderIndex();
        }
    }

    function eliminarUsuario($params = null)
    {
        $user = $this->helper->checkLogin();
        if ($user->permiso == 1) {
            $id = $params[':ID'];
            $this->modelAdmin->eliminarUsuarioID($id);
            $this->viewAdmin->ShowUsuarioLocation();
        } else {
            $this->viewAdmin->renderIndex();
        }
    }

    function permisoUsuario($params = null)
    {
        $user = $this->helper->checkLogin();
        if ($user->permiso == 1) {

            $id = $params[':ID'];

            $userId = $this->modelAdmin->getAdminID($id);

            if ($userId->permiso == 0) {
                $permiso = 1;
                $this->modelAdmin->modificarPermiso($permiso, $id);
            } else {
                $permiso = 0;
                $this->modelAdmin->modificarPermiso($permiso, $id);
            }
            $this->viewAdmin->ShowUsuarioLocation();
        } else {
            $this->viewAdmin->renderIndex();
        }
    }
}
