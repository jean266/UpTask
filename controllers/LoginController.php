<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        $alertas = Usuario::getAlertas();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);

            $alertas = $usuario->validarLogin();

            if (empty($alertas)) {
                // Verificar que el usuario este reguistrado y confirmado
                $usuario = Usuario::where('email', $usuario->email);

                if (empty($usuario) || $usuario->confirmado === '0') {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                } else {
                    // validar la password
                    if(password_verify($_POST['password'], $usuario->password)) {
                        // Llenar el arreglo de session
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionar
                        header('location: /dashboard');
                    } else {
                        // Mensaje de Error
                        Usuario::setAlerta('error', 'El password incorrecto');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();
        // Renderizar la vista
        $router->render('auth/login', [
            'titulo' => 'Login',
            'alertas' => $alertas,
        ]);
    }

    public static function logout()
    {
        session_start();
        $_SESSION = [];

        header('location: /');
    }

    public static function crear(Router $router)
    {
        $usuario = new Usuario();
        $alertas = Usuario::getAlertas();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);

            // Validacion
            $alertas = $usuario->validarNuevaCuenta();

            if (empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);

                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'El email ya esta reguistrado');
                } else {
                    // Hashear Usuario
                    $usuario->hashPassword();

                    // Eliminar Password2
                    unset($usuario->password2);

                    // Generar Token
                    $usuario->generarToken();

                    // Crear un nuevo usuario
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        // Enviar email
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarInstracionConfirmar();

                        header('location: /mensaje');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();
        // Renderizar la vista
        $router->render('auth/crear', [
            'titulo' => 'Crear tu cuenta en upTask',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router)
    {
        $alertas = Usuario::getAlertas();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);

            $alertas = $usuario->validarEmail();

            if (empty($alertas)) {
                // Buscar Usuario
                $usuario = Usuario::where('email', $usuario->email);

                if (empty($usuario) || $usuario->confirmado === '0') {
                    Usuario::setAlerta('error', 'El usuario no esta reguistrado o no esta confirmado');
                } else {
                    // Generar un nuevo token
                    $usuario->generarToken();
                    unset($usuario->password2);

                    // Actualizar el usuario
                    $usuario->guardar();


                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucionRecuperar();

                    // Imprimir la alerta
                    Usuario::setAlerta('exito', 'Hemos enviado las instruciones a tu email');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        // Renderizar la vista
        $router->render('auth/olvide', [
            'titulo' => 'Olvide Password',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router)
    {
        $token = s($_GET['token']);

        $mostrar = true;

        if (!$token) header('location: /');

        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            usuario::setAlerta('error', 'Token No Válido');
            $mostrar = false;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarPassword();

            if (empty($alertas)) {
                // Hashear el nuevo password
                $usuario->hashPassword();
                unset($usuario->password2);

                // Quitar el token
                $usuario->token = null;

                // Actualizar Usuario
                $resultado = $usuario->guardar();

                // Redicionar
                if ($resultado) {
                    header('location: /');
                }
            }
        }


        $alertas = Usuario::getAlertas();
        // Renderizar la vista
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje(Router $router)
    {
        // Renderizar la vista
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente'
        ]);
    }

    public static function confirmar(Router $router)
    {
        $token = s($_GET['token']);

        if (!$token) header('location: /');

        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            // No se encontró un usurio con ese token
            Usuario::setAlerta('error', 'El token no valido');
        } else {
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);

            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta confimada correctamente');
        }

        $alertas = Usuario::getAlertas();
        // Renderizar la vista
        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu Cuenta UpTask',
            'alertas' => $alertas
        ]);
    }
}
