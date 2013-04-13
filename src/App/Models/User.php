<?php

namespace App\Models;

class User extends AppModel {
	protected $tabela = "users";
	protected $error = "";
	/**
	 * checks if the data sent is valid to use
	 * @param array $data
	 * @return boolean
	 */
	public function isValid(array $data) {
		if(!isset($data['nome']) || empty($data['nome'])){
			$this->error = "Nome é obrigatório";
			return false;
		}
		if (!isset($data['login']) || empty($data['login'])) {
			$this->error = "Login é obrigatório";
			return false;
		}
		// login deve conter apenas letras e números, sem acentos
		if(!preg_match("/^[a-z0-9_-]{6,50}$/", strtolower($data['login']))) {
			$this->error = "Login deve conter apenas letras e números, sem acentuação e utilizando ou não unerline (_) ou hífen (-)";
			return false;
		}

		if(!isset($data['senha']) || empty($data['senha'])) {
			$this->error = "Senha é obrigatório";
			return false;
		}

		if(strlen($data['senha']) < 6) {
			$this->error = "Senha deve conter no mínimo 6 dígitos";
			return false;
		}

		if($this->loginExists( $data['login'] )) {
			$this->error = "Login já está em uso";
			return false;
		}

		return true;
	}
	/**
	 * checks if a login exists
	 * true in case it exists, false otherwise
	 * @param string $login
	 * @return boolean
	 */
	public function loginExists( $login ) {

		$sql = "SELECT COUNT(id) count FROM {$this->tabela} WHERE UPPER(login)=UPPER(?)";

		try {
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(1, strtoupper($login));
			$stmt->execute();

			return !!$stmt->fetch(\PDO::FETCH_ASSOC)['count'];
		} catch(\PDOException $e) {

		}
		return false;
	}

	/**
	 * returns the validation first error
	 * @return string
	 */
	public function getValidationError() {
		return $this->error;
	}
	/**
	 * saves a user into a database
	 * @param array &$data the data to store
	 * @return boolean
	 */
	public function save(array &$data) {
		return $this->_insert($data);
	}
	/**
	 * saves a user into a database using a INSERT sql
	 * @param array &$data
	 * @return boolean
	 */
	private function _insert(array &$data) {
		try {
			$sql = "INSERT INTO `{$this->tabela}` (`nome`, `login`, `senha`) VALUES (:nome, :login, :senha);";
			$stmt = $this->db->prepare( $sql );

			$stmt->bindParam(':nome', $data['nome']);
			$stmt->bindParam(':login', strtolower($data['login']));
			$stmt->bindParam(':senha', md5($data['senha']));
			
			return $stmt->execute();
		} catch(\PDOException $e) {
			die($e->getMessage());
		}

		return false;
	}
	/**
	 * find a user by login and senha
	 * @param string $login
	 * @param string $senha
	 * @return array
	 */
	public function findByLoginAndSenha($login, $senha)
	{
		try {
			$sql = "SELECT id, nome, login FROM {$this->tabela} WHERE login = :login AND senha = :senha";
			$stmt = $this->db->prepare($sql);
			$stmt->bindParam(":login", strtolower($login));
			$stmt->bindParam(":senha", md5($senha));
			$stmt->execute();

			return $stmt->fetch( \PDO::FETCH_ASSOC );
		} catch(\PDOException $e) {

		}

		return array();
	}
}