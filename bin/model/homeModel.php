<?php

namespace model;

use config\connect\connectDB as connectDB;

use PDO;
use PDOException;

class homeModel extends connectDB
{
    public function getInfoDashboard()
    {
        try {

            $ingreso_mensual = $this->getIngresoMensual();
            $clientes_activos = $this->getClientesActivos();

            if (!$ingreso_mensual && !$clientes_activos) {
                http_response_code(400);
                return "Error en busqueda de ingreso";
            }
            if (!$clientes_activos) {
                http_response_code(400);
                return "Error en busqueda de clientes activos";
            }
            $data = array();

            $data['ingreso'] = $ingreso_mensual['ingreso']; 
            $data['activos'] = $clientes_activos['activos']; 
        
            return $data;
        } catch (PDOException $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }

    public function getYearsPays (){
        try {


            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT DISTINCT YEAR(fecha_pago) AS anio FROM pagos ORDER BY anio DESC;";

            $stmt = $bd->prepare($sql);

            $stmt->execute();

            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    public function getHistoryPay($anio = null)
    {
        if ($anio === null) {
            $anio = date('Y');
        }

        try {
            if (!$this->valString('/^(19|20)\d{2}$/', $anio)) {
                http_response_code(400);
                return 'Carácteres inválidos';
            }

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $data = array();

            for ($mes = 1; $mes <= 12; $mes++) {

                $sql = "SELECT SUM(monto) as monto FROM pagos WHERE MONTH(fecha_pago) = :mes AND YEAR(fecha_pago) = :anio;";
                $stmt = $bd->prepare($sql);

                $stmt->execute(array(
                    ":mes" => $mes,
                    ":anio" => $anio,
                ));

                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

                $data[$mes] = $resultado && $resultado['monto'] !== null ? $resultado['monto'] : 0;
            }

            http_response_code(200);
            return json_encode($data);
        } catch (PDOException $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }

    private function getIngresoMensual(){
        try {


            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT SUM(monto) as 'ingreso' FROM pagos WHERE YEAR(fecha_pago) = YEAR(CURDATE()) AND MONTH(fecha_pago) = MONTH(CURDATE());";

            $stmt = $bd->prepare($sql);

            $stmt->execute();

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
    private function getClientesActivos(){
        try {


            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT count(id) as 'activos' FROM membresias WHERE fecha_limite > CURDATE();";

            $stmt = $bd->prepare($sql);

            $stmt->execute();

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

    private function valString($pattern, $value)
    {
        $value = trim($value);
        $matches = preg_match_all($pattern, $value);

        return $matches > 0;
    }
}
