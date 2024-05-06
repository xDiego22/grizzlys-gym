<?php

namespace model;

use config\connect\connectDB as connectDB;

use PDO;
use PDOException;


class usuariosModel extends connectDB{
    
    public function getUsers () {

        try {
            
            $users = $this->usersInfo();
            $data = array();

            foreach ($users as $user) {

                $subarray = array();
                $subarray['cedula'] = $user['cedula'];
                $subarray['nombre'] = $user['nombre'];
                $subarray['correo'] = $user['correo'];
                $subarray['telefono'] = $user['telefono'];

                $data[] = $subarray;
            }
            
            $json = array(
                "data" => $data
            );

            return json_encode($json);

        } catch (PDOException $e) {
            http_response_code(500);
            return $e->getMessage();
        }

    }

    private function usersInfo() {
        try {


            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM usuarios";

            $stmt = $bd->prepare($sql);

            $stmt->execute();

            $fila = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($fila) {
                http_response_code(200);
                return $fila;
            } else {
                http_response_code(400);
                return NULL;
            }
        } catch (PDOException $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }

    public function registerUser($cedula, $nombre, $contrasena,$contrasena2, $correo, $telefono) {
        try {

            if (
                !$this->valString('/^[0-9]{7,10}$/', $cedula) ||
                !$this->valString('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{1,50}$/', $nombre) ||
                !$this->valString('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z0-9!@#$%^&*()_+]{8,50}$/', $contrasena) ||
                !$this->valString('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $correo) ||
                !$this->valString('/^0(4\d{9})$/', $telefono)
            ) {
                    
                http_response_code(400);
                return 'Carácteres inválidos';
            }
            
            
            if($this->existUser($cedula)){
                http_response_code(400);
                return "Usuario ya existe";
            }

            if ($contrasena !== $contrasena2) {
                http_response_code(400);
                return "Contraseñas no coinciden";
            }

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $contrasena_hash = password_hash($contrasena,PASSWORD_DEFAULT,['cost'=>12]);

            $sql = "INSERT INTO usuarios (cedula, nombre, contrasena, correo, telefono) VALUES (?, ?, ?, ?, ?)";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                $cedula,
                $nombre,
                $contrasena_hash, 
                $correo, 
                $telefono,
            ));

            http_response_code(200);
            return 'Registro exitoso';
        } catch (PDOException $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }

    public function updateUser($cedula, $nombre, $contrasena,$contrasena2, $correo, $telefono){
        try{
            if (
                !$this->valString('/^[0-9]{7,10}$/', $cedula) ||
                !$this->valString('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{1,50}$/', $nombre) ||
                !$this->valString('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z0-9!@#$%^&*()_+]{8,50}$/', $contrasena) ||
                !$this->valString('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $correo) ||
                !$this->valString('/^0(4\d{9})$/', $telefono)
            ) {

                http_response_code(400);
                return 'Carácteres inválidos';
            }


            if (!$this->existUser($cedula)) {
                http_response_code(400);
                return "Usuario no existe";
            }

            if ($contrasena !== $contrasena2){
                http_response_code(400);
                return "Contraseñas no coinciden";
            }

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT, ['cost' => 12]);
    
            $sql = 'UPDATE usuarios SET 
                        nombre = ?,  
                        telefono = ?,
                        correo = ?,
                        contrasena = ?
                        where cedula = ?';
    
            $stmt = $bd->prepare($sql);
    
            $stmt->execute(array(
                $nombre,
                $telefono,
                $correo,
                $contrasena_hash,
                $cedula
            ));
    
            http_response_code(200);
            return 'Modificacion exitosa';
        } catch (PDOException $e) {
            http_response_code(500);
            return $e->getMessage();
        }

    }

    public function deleteUser($cedula){

        if(!$this->valString('/^[0-9]{7,10}$/', $cedula)){
            http_response_code(400);
            return 'Carácteres Inválidos';
        }

        if(!$this->existUser($cedula)){
            http_response_code(400);
            return 'Usuario no existe';
        }
        

        try {
            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = 'DELETE from usuarios where cedula = ?';

            $stmt = $bd->prepare($sql);

            $stmt->execute(array($cedula));

            if ($stmt) {
                http_response_code(200);
                return "Eliminado correctamente";
            } else {
                http_response_code(400);
                return "no eliminado";
            }
        } catch (PDOException $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }

    private function existUser($cedula){
        try {
            
            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT cedula FROM usuarios
            WHERE cedula = ? ";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array($cedula));

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                http_response_code(200);
                return true;
            } else {
                http_response_code(500);
                return false;
            }
            
        } catch (PDOException $e) {
            http_response_code(500);
            return 'ERROR: ' . $e->getMessage();
        }
    }

    private function valString($pattern, $value){
        $value = trim($value);
        $matches = preg_match_all($pattern, $value);

        return $matches > 0;
    }

}