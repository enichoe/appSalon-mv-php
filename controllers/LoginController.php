<?php

namespace Controller;

use Classes\Email;

use Model\Usuario;

use MVC\Router;
// include_once __DIR__ . "/../templates/alertas.php";

class LoginController{
    public static function login(Router $router) {
        $alertas = [];
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
    
            // Validar el login
            $alertas = $auth->validarLogin();
    
            if (empty($alertas)) {
                // Comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);
                if ($usuario) {
                    // Verificar la contraseña
                    $usuario->comprobarPassword($auth->password);
                    $alertas = Usuario::getAlertas();
                    if (empty($alertas['error'])) {
                        // Autenticar usuario
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
    
                        // Redireccionamiento
                        if ($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }
    
        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'alertas' => $alertas,
        ]);
    }
    
    

    public static function logout(){
        session_start();
        $_SESSION=[];
        header('Location: /');

    }
    public static function olvide(Router $router){
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth=new Usuario($_POST);
            $alertas=$auth->validarEmail();
            if (empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);
                if($usuario && $usuario->confirmado === "1"){

                    //genrear un token 
                    $usuario->crearToken();
                    $usuario->guardar();
                    //enviar email
                    $email= new Email ($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();


                    //alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu email');

                    
                }else{
                    Usuario::setAlerta('error', 'el usuario no existe o no esta confirmado');
                }
            }
        }
        $alertas=Usuario::getAlertas();

        $router->render('auth/olvide-password',[
            'alertas'=>$alertas
        ]);
    }
    public static function recuperar(Router $router){
        $alertas=[];
        $error=false;
        $token = s($_GET['token']);
        //buscar usuario por su token
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
            $error=true;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Leer nuevo password y guardarlo
            $password = new Usuario($_POST);
            $alertas=$password->validarPassword();
            if (empty($alertas)) {
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado=$usuario->guardar();
                if($resultado){
                    header('Location: /');
                }
            }

        }





        $alertas = Usuario::getAlertas();
        $router ->render('auth/recuperar-password',[
            'alertas'=>$alertas,
            'error'=>$error

        ]);
    }
    public static function crear(Router $router){
        $usuario=new Usuario;
        //ALERTAS VACIAS
        $alertas=[];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas=$usuario->validarNuevaCuenta();
            //revisar que alerta esta vacio
            if(empty($alertas)){
                //VERIFICAR QUE EL USUARIO NO ESTE REGISTRADO
                $resultado=$usuario->existeUsuarios();

                if($resultado->num_rows){
                    $alertas=Usuario::getAlertas();
                }else{
                    //hashear el password
                    $usuario->hashPassword();
                    //generar un token unico
                    $usuario->crearToken();
                    //enviar el email 
                    $email=new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    //crear usuario
                    $resultado=$usuario->guardar();
                    if($resultado){
                        header('Location: /mensaje');
                    }    
                }
            }
        }
        $router->render('auth/crear-cuenta',[
            'usuario'=>$usuario,
            'alertas'=>$alertas

        ]);
    }
    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }
    public static function confirmar(Router $router){
        $alertas=[];

        $token=s($_GET['token']);
        $usuario=Usuario::where('token',$token);
        if(empty($usuario)){
            //MOSTARR MENSAJE DE ERROR
            Usuario::setAlerta('error','Token No valido');
        }else{
            //modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito','Usuario comprobada Correctamente');
        }
        //OBTENER ALERTAS
        $alertas=Usuario::getAlertas();

        //RENDERIZAR LA VISTA
        $router->render('auth/confirmar-cuenta', [
        'alertas'=>$alertas
        ]);
    }

}