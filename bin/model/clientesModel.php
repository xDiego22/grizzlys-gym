<?php

namespace model;

use config\connect\connectDB as connectDB;

use PDO;
use PDOException;
class clientesModel extends connectDB{
    public function getClients() {

    }

    private function plansInfo($id)
    {
        try {

            // if (!$this->valString('/^[0-9]{7,10}$/', $cedula)) return;

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM planes where id = ?";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                $id
            ));

            $fila = $stmt->fetch(PDO::FETCH_ASSOC);

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

    public function registerClient($cedula, $nombre, $telefono, $plan, $monto, $fecha_inicio, $fecha_limite){
        try {

            if (
                !$this->valString('/^[0-9]{7,10}$/', $cedula) ||
                !$this->valString('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{1,50}$/', $nombre) ||
                !$this->valString('/^0(4\d{9})$/', $telefono) ||
                !$this->valString('/^[0-9]{1,10}$/', $plan) ||
                !$this->valString('/^\d+(\.\d)?$/', $monto) ||

                !$this->valString('/^(?:(?:1[6-9]|[2-9]\d)?\d{2})(?:(?:(\/|-|\.)(?:0?[13578]|1[02])\1(?:31))|(?:(\/|-|\.)(?:0?[13-9]|1[0-2])\2(?:29|30)))$|^(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(\/|-|\.)0?2\3(?:29)$|^(?:(?:1[6-9]|[2-9]\d)?\d{2})(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:0?[1-9]|1\d|2[0-8])$/', $fecha_inicio) ||

                !$this->valString('/^(?:(?:1[6-9]|[2-9]\d)?\d{2})(?:(?:(\/|-|\.)(?:0?[13578]|1[02])\1(?:31))|(?:(\/|-|\.)(?:0?[13-9]|1[0-2])\2(?:29|30)))$|^(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(\/|-|\.)0?2\3(?:29)$|^(?:(?:1[6-9]|[2-9]\d)?\d{2})(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:0?[1-9]|1\d|2[0-8])$/', $fecha_limite)

            ) {

                http_response_code(400);
                return 'Carácteres inválidos';
            }

            if ($this->existUser($cedula)) {
                http_response_code(400);
                return "Usuario ya existe";
            }

            //toda la informacion de los planes
            $precio_plan = $this->plansInfo($plan);

            if(!$precio_plan) {
                http_response_code(400);
                return "Plan no existe";
            }


            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            
            $sql = "INSERT INTO clientes (cedula, nombre, telefono,id_planes,fecha_inscripcion, estado) VALUES (?, ?, ?, ?, CURRENT_DATE,'activo')";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                $cedula,
                $nombre,
                $telefono,
                $plan,
            ));

            // <---  pago  ---->

            $id_cliente = $bd->lastInsertId();
            $deuda = ($precio_plan['valor']) - ($monto);

            $sql = "INSERT INTO pagos (id_clientes, id_planes, fecha_inicial,fecha_limite,monto,deuda) VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                $id_cliente,
                $plan,
                $fecha_inicio,
                $fecha_limite,
                $monto,
                $deuda,
            ));


            http_response_code(200);
            return 'Registro exitoso';

        } catch (PDOException $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }

    public function valorPlan($plan){
        try{
            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT valor FROM planes where id = ?";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                $plan
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

    public function getPlanes(){
        try {


            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM planes";

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