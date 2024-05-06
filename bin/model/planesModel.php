<?php

namespace model;

use config\connect\connectDB as connectDB;

use PDO;
use PDOException;

class planesModel extends connectDB
{
    public function getPlans()
    {
        try {

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM planes";

            $stmt = $bd->prepare($sql);

            $stmt->execute();

            $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $data = array();

            foreach ($plans as $plan) {

                $subarray = array();
                $subarray['id'] = $plan['id'];
                $subarray['nombre'] = $plan['nombre'];
                $subarray['valor'] = $plan['valor'];
                $subarray['descripcion'] = $plan['descripcion'];

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

    public function registerPlan($nombre, $precio,$descripcion)
    {
        try {

            if (
                !$this->valString('/^[\w\sáéíóúüñÑÁÉÍÓÚÜ0-9]{1,50}$/', $nombre) ||
                !$this->valString('/^\d+(\.\d)?$/', $precio) ||
                !$this->valString('/^[\w\sáéíóúüñÑÁÉÍÓÚÜ0-9.,\-#%+]{0,100}$/', $descripcion) 
            ) {

                http_response_code(400);
                return 'Carácteres inválidos';
            }

            if ($this->existPlan($nombre)) {
                http_response_code(400);
                return "Plan ya existe";
            }

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $sql = "INSERT INTO planes ( nombre, valor, descripcion) VALUES (?, ?, ?)";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                $nombre,
                $precio,
                $descripcion,
            ));

            http_response_code(200);
            return 'Registro exitoso';
        } catch (PDOException $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }

    private function existPlan($plan)
    {
        try {

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT nombre FROM planes
            WHERE nombre = ? ";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array($plan));

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
    private function existIdPlan($id)
    {
        try {

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT nombre FROM planes
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


    public function deletePlan($plan)
    {

        if (!$this->valString('/^[0-9]{1,50}$/', $plan)) {
            http_response_code(400);
            return 'Carácteres Inválidos';
        }

        if (!$this->existIdPlan($plan)) {
            http_response_code(400);
            return 'Plan no existe';
        }


        try {
            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = 'DELETE from planes where id = ?';

            $stmt = $bd->prepare($sql);

            $stmt->execute(array($plan));

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

    public function updatePlan($id, $nombre, $precio, $descripcion)
    {

        if (
            !$this->valString('/^[0-9]{1,50}$/', $id) ||
            !$this->valString('/^[\w\sáéíóúüñÑÁÉÍÓÚÜ0-9]{1,50}$/', $nombre) ||
            !$this->valString('/^\d+(\.\d)?$/', $precio) ||
            !$this->valString('/^[\w\sáéíóúüñÑÁÉÍÓÚÜ0-9.,\-#%+]{0,100}$/', $descripcion)
            ) {
            http_response_code(400);
            return 'Carácteres Inválidos';
        }

        if (!$this->existIdPlan($id)) {
            http_response_code(400);
            return 'Plan no existe';
        }


        try {
            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = 'UPDATE planes 
                    SET nombre = ?,
                        valor = ?,
                        descripcion = ? 
                    WHERE id = ?';

            $stmt = $bd->prepare($sql);

            $stmt->execute(array($nombre,$precio,$descripcion,$id));

            http_response_code(200);
            return "Modificado correctamente";

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
