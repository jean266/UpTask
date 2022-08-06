<?php

namespace Controllers;

use Model\Tarea;
use Model\Proyecto;

class TareaController
{
    public static function index()
    {

        $proyectoId = $_GET['id'];

        if (!$proyectoId) header('location: /dashboard');

        $proyecto = Proyecto::where('url', $proyectoId);
        session_start();
        if (!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) header('location: /404');

        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);

        echo json_encode(['tareas' => $tareas]);
    }

    public static function crear()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();

            $proyectoId = $_POST['proyectoId'];

            $proyecto = Proyecto::where('url', $proyectoId);

            if (!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al agregar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            // Todo bien, instanciar y crear la terea
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea Creada Correctamente',
                'proyectoId' => $proyecto->id
            ];
            echo json_encode($respuesta);
        }
    }

    public static function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar que el proyecto exista 
            session_start();
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);

            if (!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();

            if ($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'mensaje' => 'Actualizado Correctamente',
                    'proyectoId' => $proyecto->id
                ];
                echo json_encode($respuesta);
            }
        }
    }

    public static function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            session_start();
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);

            if (!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al eliminar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            $tarea = new Tarea($_POST);
            $resultado = $tarea->eliminar();

            if ($resultado) {
                $respuesta = [
                    'resultado' => $resultado,
                    'tipo' => 'exito',
                    'mensaje' => 'Eliminado Correctamente'
                ];
                echo json_encode($respuesta);
            }
        }
    }
}
