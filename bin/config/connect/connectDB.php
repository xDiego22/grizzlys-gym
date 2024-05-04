<?php 
	namespace config\connect;
	use config\components\configSystem as configSystem;
	use PDO;
	use PDOException;

class connectDB extends configSystem{

	private $usuario;
	private $password;
	private $local;
	private $nameDB;

	public function __construct(){

		$this->usuario = parent::_USER_();
		$this->password = parent::_PASS_();
		$this->local = parent::_LOCAL_();
		$this->nameDB = parent::_BD_();
	}

	protected function conexion(){
		try{
			$pdo = new PDO("mysql:host={$this->local};dbname={$this->nameDB}", $this->usuario , $this->password);

			$pdo->exec("SET NAMES 'utf8'");
			return $pdo;
		}catch (PDOException $e) {
			print "¡Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	
}
