<?php

namespace model;
use config\connect\connectDB as connectDB;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDO;
use PDOException;

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../.');
$dotenv->load();

class loginModel extends connectDB{

    private $cedula;
    private $contrasena;

    public function login($cedula, $contrasena) {

        try {
            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $userInfo = $this->userInfo($cedula);

            if(!$userInfo){
                http_response_code(400);
                return json_encode(
                    [
                        "statusCode" => 400,
                        "statusMessage" => "Bad Request",
                        "message" => "usuario no existe",
                    ]
                );
            }

            if(!$this->valString('/^[0-9]{7,10}$/',$cedula) ){
                http_response_code(400);
                return json_encode(
                    [
                        "statusCode" => 400,
                        "statusMessage" => "Bad Request",
                        "message" => "valores incorrectos",
                    ]
                );
            }

            if(!password_verify($contrasena, $userInfo['contrasena'])){
                http_response_code(400);
                return json_encode(
                    [
                        "statusCode" => 400,
                        "statusMessage" => "Bad Request",
                        "message" => "contraseña incorrecta",
                    ]
                );
            }

            $key = $_ENV['JWT_SECRET_KEY'];

            $payload = [
                'iat' => time(), //tiempo de emision del token
                'exp' => time() + 3600, //tiempo de expiracion del token (1 hora)
                'data' => $userInfo['cedula'],
            ];

            $jwt = JWT::encode($payload, $key, 'HS256');

            http_response_code(200);

            return json_encode(
                [
                    "statusCode" => 200,
                    "statusMessage" => "success",
                    "message" => "ok",
                    "jwt" => $jwt,
                    "cedula" => $userInfo['cedula'],
                    "nombre" => $userInfo['nombre'],
                    "correo" => $userInfo['correo'],
                    "telefono" => $userInfo['telefono'],
                ]
            );
            
        } catch (PDOException $e) {
            http_response_code(500);
            return $e->getMessage();
        }

    }

    private function userInfo($cedula){
        try {

            if (!$this->valString('/^[0-9]{7,10}$/', $cedula)) return;

            $bd = $this->conexion();
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM usuarios where cedula = ?";

            $stmt = $bd->prepare($sql);

            $stmt->execute(array(
                $cedula
            ));

            $fila = $stmt->fetch(PDO::FETCH_ASSOC);

            if($fila) {
                http_response_code(200);
                return $fila;
            }else{
                http_response_code(400);
                return NULL;
            }

        } catch (PDOException $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }

    private function valString($pattern, $value){
        $value = trim($value);

        return preg_match_all($pattern,$value);

    }
}