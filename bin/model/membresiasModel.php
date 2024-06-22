<?php

namespace model;

use config\connect\connectDB as connectDB;

use PDO;
use PDOException;
use Exception;

class membresiasModel extends connectDB
{
    public function getClients()
    {
        try {
            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT clientes.id as id,
                    clientes.cedula as cedula,
                    clientes.nombre AS nombre,
                    
                    CASE WHEN membresias.fecha_limite > CURDATE() THEN 'activo' ELSE 'vencido' END AS estado,             membresias.fecha_inicial as f_inicial, 
                    membresias.fecha_limite as f_limite, 
                    TIMESTAMPDIFF(DAY,CURDATE(),membresias.fecha_limite) as dias_restantes
                    FROM clientes INNER JOIN membresias ON clientes.id = membresias.id_clientes;";

            $stmt = $bd->prepare($sql);

            $stmt->execute();

            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $data = array();

            foreach ($clients as $client) {

                $subarray = array();
                $subarray['id'] = $client['id'];
                $subarray['cedula'] = $client['cedula'];
                $subarray['nombre'] = $client['nombre'];
                $subarray['f_inicial'] = $client['f_inicial'];
                $subarray['f_limite'] = $client['f_limite'];
                $subarray['dias_restantes'] = $client['dias_restantes'];
                $subarray['estado'] = $client['estado'];

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

    
    public function updateMembership($id, $fecha_inicial,$fecha_limite)
    {
        try {

            if (
                !$this->valString('/^[0-9]{1,50}$/', $id) ||

                !$this->valString('/^(?:(?:1[6-9]|[2-9]\d)?\d{2})(?:(?:(\/|-|\.)(?:0?[13578]|1[02])\1(?:31))|(?:(\/|-|\.)(?:0?[13-9]|1[0-2])\2(?:29|30)))$|^(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(\/|-|\.)0?2\3(?:29)$|^(?:(?:1[6-9]|[2-9]\d)?\d{2})(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:0?[1-9]|1\d|2[0-8])$/', $fecha_inicial) ||

                !$this->valString('/^(?:(?:1[6-9]|[2-9]\d)?\d{2})(?:(?:(\/|-|\.)(?:0?[13578]|1[02])\1(?:31))|(?:(\/|-|\.)(?:0?[13-9]|1[0-2])\2(?:29|30)))$|^(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(\/|-|\.)0?2\3(?:29)$|^(?:(?:1[6-9]|[2-9]\d)?\d{2})(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:0?[1-9]|1\d|2[0-8])$/', $fecha_limite)
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

            // Comenzar la transacción
            $bd->beginTransaction();

            $sql = "UPDATE membresias SET fecha_inicial = :fecha_inicial, fecha_limite = :fecha_limite WHERE id_clientes = :id";

            $stmt = $bd->prepare($sql);
            $stmt->execute(array(":id" => $id, ":fecha_inicial" => $fecha_inicial, ":fecha_limite" => $fecha_limite));

            // Confirmar la transacción
            $bd->commit();
            http_response_code(200);
            return 'Modificacion exitosa';
        } catch (PDOException $e) {
            // Revertir la transacción en caso de error
            $bd->rollBack();
            http_response_code(500);
            return $e->getMessage();
        }
    }
   
    private function existIdUser($id)
    {
        try {

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT id FROM clientes
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

    public function clientsInfo()
    {
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

    private function valString($pattern, $value)
    {
        $value = trim($value);
        $matches = preg_match_all($pattern, $value);

        return $matches > 0;
    }
}
