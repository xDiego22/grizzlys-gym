<?php

namespace model;

use config\connect\connectDB as connectDB;

use PDO;
use PDOException;

class pagosModel extends connectDB {

    public function getPays()
    {
        try {
            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT pagos.id AS 'id', usuarios.nombre AS 'register_by', clientes.cedula AS 'cedula', clientes.nombre AS 'nombre', pagos.fecha_pago AS 'fecha_pago', pagos.monto AS 'monto' FROM pagos INNER JOIN clientes on clientes.id = pagos.id_clientes INNER JOIN usuarios on usuarios.cedula = pagos.id_usuarios ORDER BY pagos.fecha_pago DESC;";

            $stmt = $bd->prepare($sql);

            $stmt->execute();

            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $data = array();

            foreach ($clients as $client) {

                $subarray = array();
                $subarray['id'] = $client['id'];
                $subarray['register_by'] = $client['register_by'];
                $subarray['cedula'] = $client['cedula'];
                $subarray['nombre'] = $client['nombre'];
                $subarray['fecha_pago'] = $client['fecha_pago'];
                $subarray['monto'] = $client['monto'];
                
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

    public function getClients(){
        try {

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT id,cedula,nombre FROM clientes";

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

    public function clientPay($id, $monto, $usuario_sesion)
    {
        try {

            if (
                !$this->valString('/^[0-9]{1,50}$/', $id) ||
                !$this->valString('/^\d+(\.\d)?$/', $monto) ||
                !$this->valString('/^[0-9]{7,10}$/', $usuario_sesion)
            ) {
                http_response_code(400);
                return 'Carácteres inválidos';
            }

            if (!$this->existIdUser($id)) {
                http_response_code(400);
                return "Usuario no existe";
            }

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $bd->beginTransaction();

            //registro del pago

            $sql = "INSERT INTO pagos (id_usuarios,id_clientes, fecha_pago, monto) 
            VALUES (:id_usuarios,:id, CURDATE(), :monto)";

            $stmt = $bd->prepare($sql);
            $stmt->execute(array(
                ":id"           => $id,
                ":id_usuarios"  => $usuario_sesion,
                ":monto"        => $monto,
            ));

            //actualizacion de saldo
            $sql = "UPDATE clientes 
            SET saldo = saldo + (:monto) 
            WHERE id = :id";

            $stmt = $bd->prepare($sql);
            $stmt->execute(array(
                ":id"       => $id,
                ":monto"    => $monto,
            ));

            $bd->commit();
            http_response_code(200);
            return 'Pago exitoso';
        } catch (PDOException $e) {
            // Revertir la transacción en caso de error
            $bd->rollBack();
            http_response_code(500);
            return $e->getMessage();
        }
    }

    public function infoClientPay($id)
    {
        try {

            if (!$this->valString('/^[0-9]{1,50}$/', $id)) {
                http_response_code(400);
                return 'Carácteres inválidos';
            }

            if (!$this->existIdUser($id)) {
                http_response_code(400);
                return "Usuario no existe";
            }

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT cedula, nombre, saldo FROM clientes c WHERE id = ?;";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                $id
            ));

            $fila = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$fila) {
                http_response_code(400);
                return NULL;
            }

            http_response_code(200);

            return json_encode($fila);
        } catch (PDOException $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }

    private function existIdUser($id)
    {
        try {

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT cedula FROM clientes
            WHERE id = ? ";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array($id));

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