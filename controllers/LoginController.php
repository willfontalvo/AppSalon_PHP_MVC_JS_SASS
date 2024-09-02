<?php

    namespace Controllers;

use Clases\Email;
use Model\Usuario;
use MVC\Router;

    class LoginController{
        public static function login(Router $router){
            $alertas = [];
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $auth= new Usuario($_POST);
                $alertas = $auth->validarLogin();

                if(empty($alertas)){
                    //comprobar que exista el usuario
                    $usuario = Usuario::where('email', $auth->email);
                    if($usuario){
                        //verificar el password
                       if($usuario->comprobarPasswordAndVerificado($auth->password)) {
                            //autenticar el usuario
                            session_start();

                            $_SESSION['id'] = $usuario->id;
                            $_SESSION['nombre'] = $usuario->nombre . "  " . $usuario->apellido;
                            $_SESSION['email'] = $usuario->email;
                            $_SESSION['login'] = true;

                            //redireccionamiento
                            if($usuario->admin === "1"){
                                $_SESSION['admin'] = $usuario->admin ?? null;
                                header('Location: /admin');
                            } else{
                                header('Location: /citas');
                            }

                            

                       }
                    }else{
                        Usuario::setAlerta('error', 'Usuario no existe');
                    }
                }

            }

            $alertas = Usuario::getAlertas();

            $router -> render('auth/login',[
                'alertas' => $alertas
          ]) ;
        }

        public static function logout(){
            session_start();
            $_SESSION=[];
            header('Location:/');
        }

        public static function olvide(Router $router){
            $alertas = [];

            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $auth = new Usuario($_POST);
                $alertas = $auth->validarEmail();

                if(empty($alertas)){
                    $usuario = Usuario::where('email', $auth->email);

                    if($usuario && $usuario->confirmado==='1'){
                        //generar token
                        $usuario->crearToken();
                        $usuario->guardar();

                        //TODO: enviar el email
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarInstrucciones();

                        //alerta de exito
                        Usuario::setAlerta('exito', 'revisa tu email');
                        

                    }else{
                        Usuario::setAlerta('error', 'el usuario no existe o no esta confirmado');
                       
                    }

                }
            }

            $alertas = Usuario::getAlertas();
            
            $router -> render('auth/olvide-password', [
                'alertas'=>$alertas
            ]);
        }

        public static function recuperar(Router $router){
            $alertas = [];
            $error = false;

            $token = s($_GET['token']);

            //buscar usuario por token

            $usuario = Usuario::where('token', $token);

            if(empty($usuario)){
                Usuario::setAlerta('error', 'Token No Valido');
                $error = true;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST'){
                //LEER EL NUEVO PASSWORD Y GUARDARLO
                $password = new Usuario($_POST);
                $alertas = $password -> validarPassword();

                if(empty($alertas)){
                    $usuario->password =null;
                    $usuario ->password = $password ->password;
                    $usuario -> hashPassword();
                    $usuario -> token = null;
                    $resultado = $usuario -> guardar();

                    if($resultado){
                        header('Location: /'); 
                    }

                }
            }
            //debuguear($usuario);
            $alertas = Usuario::getAlertas();
            $router->render('auth/recuperar-password', [
                'alertas' => $alertas,
                'error'=> $error
            ]);
        }

        public static function crear(Router $router){
            $usuario = new Usuario;
            //alertas vacias
            $alertas = [];

            if($_SERVER['REQUEST_METHOD']=== 'POST'){
                $usuario->sincronizar($_POST);
                $alertas = $usuario->validarCuenta();

                // revisar que alertas esta vacio
                if(empty($alertas)){
                    //verificar que el usuario no este verificado
                    $resultado = $usuario->existeUsuario();

                    if($resultado->num_rows){
                        $alertas = Usuario::getAlertas();
                    } else{
                        //no esta registrado
                        //hashear password
                        $usuario->hashPassword();
                        //crear token unico
                        $usuario->crearToken();
                        // enviar email
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                        $email->enviarConfirmacion();

                        //crear el usuario
                        $resultado = $usuario->guardar();
                        if($resultado){
                            header('Location: /mensaje');
                        }
                        //debuguear($usuario);
                    }
                }
                
            }
            $router -> render('auth/crear-cuenta', [
                'usuario'=> $usuario,
                'alertas'=> $alertas
            ]);
        }

        public static function mensaje(Router $router){
            $router ->render ('auth/mensaje');
        }

        public static function confirmar(Router $router){
            $alertas = [];
            $token = s($_GET['token']);

            $usuario = Usuario::where('token', $token);
            
            if(empty($usuario)){
                //mostrar mensaje de error
                Usuario::setAlerta('error', 'token no valido');
            } else{
                //modificar a usuario confirmado
                $usuario->confirmado = '1';
                $usuario->token = '';
                $usuario->guardar();
                Usuario::setAlerta('exito', 'su cuenta ha sido confirmada correctamente');
                

            }
            //obtener alertas
            $alertas = Usuario::getAlertas();
            //renderizar la vista
            $router->render('auth/confirmar-cuenta', [
                'alertas'=> $alertas
            ]);
        }
    }

?>