<?php

namespace Model;

class Proyecto extends ActiveRecord 
{
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id', 'proyecto', 'url', 'propietarioId'];

    public $id;
    public $proyecto;
    public $url;
    public $propietarioId;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->propietarioId = $args['propietarioId'] ?? '';
    }

    // Validacion al crear un nuevo Proyecto
    public function validarProyecto()
    {
        if(!$this->proyecto) {
            self::$alertas['error'][] = "El nombre del proyecto es obligatorio";
        }
        return self::$alertas;
    }

    // Genera un URL unica
    public function generarURL()
    {
        $this->url = md5(uniqid());
    }
}