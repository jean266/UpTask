<?php

namespace Model;

class Usuario extends ActiveRecord
{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? null;
        $this->password_actual = $args['password_actual'] ?? null;
        $this->password_nuevo = $args['password_nuevo'] ?? null;
        $this->repetir_password = $args['repetir_password'] ?? null;
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    // Validar de login
    public function validarLogin() : array
    {
        if (!$this->email) {
            self::$alertas['error'][] = "El email de usuario es obligatorio";
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = "El email no valido";
        }
        if (!$this->password) {
            self::$alertas['error'][] = "El password no puede ir vacio";
        }

        return self::$alertas;
    }

    // Validacion de Crear
    public function validarNuevaCuenta() : array
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = "El nombre de usuario es obligatorio";
        }

        if (!$this->email) {
            self::$alertas['error'][] = "El email del usuario es obligatorio";
        }

        if (!$this->password) {
            self::$alertas['error'][] = "El password no puede ir vacio";
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = "El password debe contener almenos 6 caracteres";
        }

        if ($this->password !== $this->password2) {
            self::$alertas['error'][] = "Los passwords son diferentes";
        }

        return self::$alertas;
    }

    // Validar Email
    public function validarEmail() : array
    {
        if (!$this->email) {
            self::$alertas['error'][] = "El email de usuario es obligatorio";
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = "El email no valido";
        }

        return self::$alertas;
    }

    // Validar password
    public function validarPassword() : array
    {
        if (!$this->password) {
            self::$alertas['error'][] = "El password no puede ir vacio";
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = "El password debe contener almenos 6 caracteres";
        }

        if ($this->password !== $this->password2) {
            self::$alertas['error'][] = "Los passwords son diferentes";
        }

        return self::$alertas;
    }

    // Valida el perfil
    public function validarPerfil() : array
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = "El nombre es obligatorio";
        }

        if (!$this->email) {
            self::$alertas['error'][] = "El email es obligatorio";
        }
        return self::$alertas;
    }

    public function validarCambiarPassword() : array
    {
        if (!$this->password_actual) {
            self::$alertas['error'][] = "El password actual no puede ir vacio";
        }

        if (!$this->password_nuevo) {
            self::$alertas['error'][] = "El password nuevo no puede ir vacio";
        }

        if (strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = "El password debe contener almenos 6 caracteres";
        }

        if ($this->password_nuevo !== $this->repetir_password) {
            self::$alertas['error'][] = "Los passwords son diferentes";
        }
        return self::$alertas;
    }

    // comprueba el password
    public function comprabarPassword() : bool
    {
        return password_verify($this->password_actual, $this->password);
    }

    // Hashea el password
    public function hashPassword() : void
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function generarToken() : void
    {
        $this->token = md5(uniqid());
    }
}
