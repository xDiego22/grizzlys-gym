<?php

namespace model;

use config\connect\connectDB as connectDB;

use PDO;
use PDOException;
class clientesModel extends connectDB{
    public function getClients() {

    }

    public function registerClient($cedula, $nombre, $telefono, $membresia){
        try {

            if (
                !$this->valString('/^[0-9]{7,10}$/', $cedula) ||
                !$this->valString('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{1,50}$/', $nombre) ||
                !$this->valString('/^0(4\d{9})$/', $telefono) ||
                !$this->valString('/^[0-9]{1,10}$/', $membresia)
            ) {

                http_response_code(400);
                return 'Carácteres inválidos';
            }

            if ($this->existUser($cedula)) {
                http_response_code(400);
                return "Usuario ya existe";
            }

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            
            $sql = "INSERT INTO clientes (cedula, nombre, telefono,id_membresias,fecha_inscripcion, estado) VALUES (?, ?, ?, ?, CURRENT_DATE,'activo')";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                $cedula,
                $nombre,
                $telefono,
                $membresia,
            ));

            http_response_code(200);
            return 'Registro exitoso';

        } catch (PDOException $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }

    public function valorMembresia($membresia){
        try{
            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT valor FROM membresias where id = ?";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                $membresia
            ));

            $fila = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($fila) {
                http_response_code(200);

                return json_encode($fila);
            } else {
                http_response_code(400);
                return NULL;
            }
        } catch (PDOException $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }

    public function getMembresias(){
        try {


            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM membresias";

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

    private function existUser($cedula)
    {
        try {

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT cedula FROM clientes
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

    private function valString($pattern, $value)
    {
        $value = trim($value);
        $matches = preg_match_all($pattern, $value);

        return $matches > 0;
    }
}