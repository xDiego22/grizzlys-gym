<?php

namespace model;

use config\connect\connectDB as connectDB;

use PDO;
use PDOException;
use Exception;
class clientesModel extends connectDB{
    public function getClients() {
        try {
            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT clientes.id as id,
                    clientes.cedula as cedula,
                    clientes.nombre AS nombre_cliente,
                    clientes.telefono as telefono, 
                    planes.id as id_plan,
                    planes.nombre as nombre_plan,
                    CASE WHEN MAX(pagos.fecha_limite) > CURDATE() THEN 'activo' ELSE 'vencido' END AS estado,
                    MAX(pagos.fecha_inicial) as f_inicial, 
                    MAX(pagos.fecha_limite) as f_limite, 
                    TIMESTAMPDIFF(DAY,CURDATE(),MAX(pagos.fecha_limite)) as dias_restantes ,
                    clientes.saldo as saldo FROM clientes INNER JOIN pagos ON clientes.id = pagos.id_clientes INNER JOIN planes ON clientes.id_planes = planes.id GROUP BY clientes.id;";

            $stmt = $bd->prepare($sql);

            $stmt->execute();

            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $data = array();

            foreach ($clients as $client) {

                $subarray = array();
                $subarray['id'] = $client['id'];
                $subarray['cedula'] = $client['cedula'];
                $subarray['nombre'] = $client['nombre_cliente'];
                $subarray['telefono'] = $client['telefono'];
                $subarray['id_plan'] = $client['id_plan'];
                $subarray['plan'] = $client['nombre_plan'];
                $subarray['estado'] = $client['estado'];
                $subarray['f_inicial'] = $client['f_inicial'];
                $subarray['f_limite'] = $client['f_limite'];
                $subarray['dias_restantes'] = $client['dias_restantes'];
                $subarray['saldo'] = $client['saldo'];

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

            // Comenzar la transacción
            $bd->beginTransaction();
            
            $sql = "INSERT INTO clientes (cedula, nombre, telefono,id_planes,fecha_inscripcion,saldo) VALUES (?, ?, ?, ?, CURRENT_DATE, ?)";

            $saldo = ($monto) - ($precio_plan['valor']) ;

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                $cedula,
                $nombre,
                $telefono,
                $plan,
                $saldo,
            ));

            // <---  pago  ---->

            $id_cliente = $bd->lastInsertId();

            $sql = "INSERT INTO pagos (id_clientes, id_planes, fecha_inicial,fecha_limite,monto) VALUES (?, ?, ?, ?, ?)";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                $id_cliente,
                $plan,
                $fecha_inicio,
                $fecha_limite,
                $monto,
            ));

            // Confirmar la transacción
            $bd->commit();
            http_response_code(200);
            return 'Registro exitoso';

        } catch (PDOException $e) {
            // Revertir la transacción en caso de error
            $bd->rollBack();
            http_response_code(500);
            return $e->getMessage();
        }
    }
    public function updateClient($id,$cedula, $nombre, $telefono, $plan){
        try {

            if (
                !$this->valString('/^[0-9]{1,50}$/', $id) ||
                !$this->valString('/^[0-9]{7,10}$/', $cedula) ||
                !$this->valString('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{1,50}$/', $nombre) ||
                !$this->valString('/^0(4\d{9})$/', $telefono) ||
                !$this->valString('/^[0-9]{1,10}$/', $plan)
            ) {

                http_response_code(400);
                return 'Carácteres inválidos';
            }

            if (!$this->existUser($cedula)) {
                http_response_code(400);
                return "Usuario no existe";
            }

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Comenzar la transacción
            $bd->beginTransaction();

            // Obtener el precio del plan actual del cliente antes de modificar
            $sql = "SELECT valor FROM planes WHERE id = (SELECT id_planes FROM clientes WHERE cedula = :cedula);";
            $stmt = $bd->prepare($sql);
            $stmt->execute(array(":cedula" => $cedula));
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$resultado) {
                http_response_code(400);
                throw new Exception("No se encontró el plan actual del cliente.");
            }

            $valorActual = $resultado['valor'];

            // Obtener el precio del nuevo plan
            $sql = "SELECT valor FROM planes WHERE id = :plan";
            $stmt = $bd->prepare($sql);
            $stmt->execute(array(":plan" => $plan));
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$resultado) {
                http_response_code(400);
                throw new Exception("No se encontró el nuevo plan.");
            }

            $valorNuevo = $resultado['valor'];

            // Actualizar el cliente con el nuevo plan y el saldo ajustado
            $sql = "UPDATE clientes 
            SET cedula = :cedula, nombre = :nombre, telefono = :telefono, id_planes = :plan, saldo = saldo + (:valorActual - :valorNuevo) 
            WHERE id = :id";

            $stmt = $bd->prepare($sql);
            $stmt->execute(array(
                ":id"       => $id,
                ":cedula"   => $cedula,
                ":nombre"   => $nombre,
                ":telefono" => $telefono,
                ":plan"     => $plan,
                ":valorActual" => $valorActual,
                ":valorNuevo"  => $valorNuevo,
            ));

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
    public function deleteClient($id){
        try {

            
            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Comenzar la transacción
            $bd->beginTransaction();
            
            $sql = "DELETE FROM clientes where id = ?";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                $id
            ));


            // Confirmar la transacción
            $bd->commit();
            http_response_code(200);
            return 'eliminado';

        } catch (PDOException $e) {
            // Revertir la transacción en caso de error
            $bd->rollBack();
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