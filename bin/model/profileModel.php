<?php

namespace model;

use config\connect\connectDB as connectDB;

use PDO;
use PDOException;

class profileModel extends connectDB
{
    public function consultarPerfil($cedula_sesion)
    {
        try {


            if (
                !$this->valString('/^[0-9]{7,10}$/', $cedula_sesion)
            ) {
                http_response_code(400);
                return 'Carácteres inválidos';
            }

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM usuarios WHERE cedula = :cedula LIMIT 1";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                ":cedula" => $cedula_sesion
            ));

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                http_response_code(200);
                return $resultado;
            } else {
                http_response_code(400);
                return NULL;
            }
        } catch (PDOException $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }

    public function cambiarContrasena($cedula_sesion, $contrasena_actual,$contrasena, $contrasena2)
    {
        try {

        
            if (
                !$this->valString('/^[0-9]{7,10}$/', $cedula_sesion) ||
                !$this->valString('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z0-9!@#$%^&*()_+]{8,50}$/', $contrasena_actual) ||
                !$this->valString('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z0-9!@#$%^&*()_+]{8,50}$/', $contrasena) ||
                !$this->valString('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z0-9!@#$%^&*()_+]{8,50}$/', $contrasena2)
            ) {
                http_response_code(400);
                return 'Carácteres inválidos';
            }

            if (!$this->existUser($cedula_sesion)) {
                http_response_code(400);
                return "Usuario no existe";
            }

            if (!$this->verifyPassword($cedula_sesion, $contrasena_actual)) {
                http_response_code(400);
                return "Contraseña no coincide con la actual";
            }

            if ($contrasena !== $contrasena2) {
                http_response_code(400);
                return "Contraseñas no coinciden";
            }

            $contrasena_hash = password_hash($contrasena2, PASSWORD_DEFAULT, ['cost' => 12]);
            
            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE usuarios SET contrasena = :contrasena WHERE cedula = :cedula";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                ":cedula" => $cedula_sesion,
                ":contrasena" => $contrasena_hash,
            ));

            http_response_code(200);
        return "Contraseña modificada";
        } catch (PDOException $e) {
            http_response_code(500);
            return $e->getMessage();
        }
        
    }

    public function editarPerfil($cedula_sesion, $nombre, $correo, $telefono)
    {
        try {

            if (
                !$this->valString('/^[0-9]{7,10}$/', $cedula_sesion) ||
                !$this->valString('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{1,50}$/', $nombre) ||
                !$this->valString('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $correo) ||
                !$this->valString('/^0(4\d{9})$/', $telefono)
            ) {
                http_response_code(400);
                return 'Carácteres inválidos';
            }

            if (!$this->existUser($cedula_sesion)) {
                http_response_code(400);
                return "Usuario no existe";
            }

            if ($this->existCorreo($correo) && $cedula_sesion != $this->propietarioCorreo($correo)){
                http_response_code(400);
                return "Correo pertenece a otro usuario";
            }

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE usuarios set nombre = :nombre, correo = :correo, telefono = :telefono WHERE cedula = :cedula";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                ":cedula" => $cedula_sesion,
                ":nombre" => $nombre,
                ":correo" => $correo,
                ":telefono" => $telefono,
            ));
            
            $data = array(
                "message" => "Modificacion exitosa",
                "nombre" => $nombre,
                "correo" => $correo,
                "telefono" => $telefono,
            );

            http_response_code(200);
            return json_encode($data);
            
        } catch (PDOException $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }

    
    private function valString($pattern, $value)
    {
        $value = trim($value);
        $matches = preg_match_all($pattern, $value);

        return $matches > 0;
    }

    private function existUser($cedula)
    {
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
    private function existCorreo($correo)
    {
        try {

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT correo FROM usuarios WHERE correo = :correo ";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                ":correo" => $correo
            ));

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
    private function propietarioCorreo($correo)
    {
        try {

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT cedula FROM usuarios WHERE correo = :correo LIMIT 1";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                ":correo" => $correo
            ));

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                http_response_code(200);
                return $resultado['cedula'];
            } else {
                http_response_code(500);
                return false;
            }
        } catch (PDOException $e) {
            http_response_code(500);
            return 'ERROR: ' . $e->getMessage();
        }
    }

    private function verifyPassword($cedula_sesion, $contrasena_actual){
        try {

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT contrasena from usuarios where cedula = :cedula LIMIT 1";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                ":cedula" => $cedula_sesion
            ));

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                http_response_code(200);
                $contrasena_encontrada = $resultado['contrasena'];

                if(password_verify($contrasena_actual, $contrasena_encontrada)){

                    return true;
                }else{
                    return false;
                }
            } else {
                http_response_code(400);
                return false;
            }
        } catch (PDOException $e) {
            http_response_code(500);
            return 'ERROR: ' . $e->getMessage();
        }
    }
}
