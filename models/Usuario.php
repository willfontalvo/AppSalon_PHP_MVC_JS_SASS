<?php

namespace Model;

use Model\ActiveRecord;

class Usuario extends ActiveRecord{
    //base datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'telefono', 'password', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $telefono;
    public $password;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->admin = $args['admin'] ?? 0;
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->token = $args['token'] ?? '';
    }

    // mensajes de validacion para la cracion de una cuenta
    public function  validarCuenta(){
        if(!$this->nombre){
            self::$alertas['error'] [] ='El nombre del cliente es obligatorio';
        }

        if(!$this->apellido){
            self::$alertas['error'] [] = 'apellido del cliente es obligatorio';
        }

        if(!$this->email){
            self::$alertas['error'] [] = 'el email del cliente es obligatorio';
        }

        if(!$this->telefono){
            self::$alertas['error'] [] = 'el telefono del cliente es obligatorio';
        }

        if(!$this->password){
            self::$alertas['error'] [] = 'el password es obligatorio';
        }

        if(strlen($this->password) < 8){
            self::$alertas['error'] [] = 'el password debe tener al menos 8 caracteres';
        }
        return self::$alertas;
    }

    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'] [] = 'el email es obligatorio';
        }

        if(!$this->password){
            self::$alertas['error'] [] = 'el password es obligatorio';
        }

        return self::$alertas;
    }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'] [] = 'el email es obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'] [] = 'El password es obligatorio';
        }

        if(strlen($this->password) < 6){
            self::$alertas['error'] [] = 'El password debe tener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    public function existeUsuario(){
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        $resultado = self::$db->query($query);

        if($resultado->num_rows){
            self::$alertas['error'] [] = 'El usuario ya esta registrado';
        }

        return $resultado;
    }

    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password){
        $resultado = password_verify($password, $this->password);
        if(!$resultado || !$this->confirmado){
            self::$alertas['error'] []= 'password incorrecto o tu cuenta no ha sido confirada';
        } else{
            return true;
        }
    }
}