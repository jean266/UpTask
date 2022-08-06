<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController
{
    public static function index(Router $router)
    {
        session_start();
        isAuth();

        $proyectos = Proyecto::belongsTo('propietarioId', $_SESSION['id']);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router)
    {
        session_start();
        isAuth();

        $alertas = Proyecto::getAlertas();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);

            // Validacion
            $alertas = $proyecto->validarProyecto();

            if (empty($alertas)) {
                // Generar una URL unica
                $proyecto->generarURL();

                // guardar el id del propetario
                $proyecto->propietarioId = $_SESSION['id'];

                // Guardar el Proyecto
                $resultado = $proyecto->guardar();

                // Redirecionar
                if ($resultado) {
                    header('location: /proyecto?id=' . $proyecto->url);
                }
            }
        }

        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router)
    {
        session_start();
        isAuth();

        $alertas = Proyecto::getAlertas();

        $url = $_GET['id'];
        if (!$url) header('location: /dashboard');

        // Revisar que la persona que visita el proyecto, es quien es lo creo
        $proyecto = Proyecto::where('url', $url);

        if ($proyecto->propietarioId !== $_SESSION['id']) {
            header('location: /dashboard');
        }


        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto,
            'alertas' => $alertas
        ]);
    }

    public static function perfil(Router $router)
    {
        session_start();
        isAuth();

        $alertas = Usuario::getAlertas();

        $usuario = Usuario::find($_SESSION['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarPerfil();

            if (empty($alertas)) {
                // Verificar que el email no este reguistrado antes de actualizar
                $existeUsuario = Usuario::where('email', $usuario->email);

                if ($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    // Mensaje el error
                    Usuario::setAlerta('error', 'Email no valido, cuenta ya reguistrada');
                } else {
                    // Guardar el usuario
                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        $_SESSION['nombre'] = $usuario->nombre;
                        Usuario::setAlerta('exito', 'Imformacion guardada correctamente');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function cambiar_password(Router $router) 
    {
        session_start();
        isAuth();

        $alertas = Usuario::getAlertas();

        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);
            $usuario->sincronizar($_POST);
            
            $alertas = $usuario->validarCambiarPassword();
            
            if(empty($alertas)) {
                // validar que la contraseña coincidan
                $resultado = $usuario->comprabarPassword();
                if($resultado) {
                    
                    $usuario->password = $usuario->password_nuevo;

                    // Eliminar propiedades innecesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);
                    unset($usuario->password2);
                    unset($usuario->repetir_password);
                    
                    // Hashear el nuevo password
                    $usuario->hashPassword();

                    // Actualizar
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        Usuario::setAlerta('exito', 'El password fue Actulizado correctamente');
                    }
                } else {
                    Usuario::setAlerta('error', 'El password actual no es correcta');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas
        ]);
    }
}
