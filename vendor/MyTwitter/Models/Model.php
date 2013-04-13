<?php

namespace MyTwitter\Models;

class Model {
	protected $db;
	protected $tabela;
	protected $error = "";
	
	public function __construct(\PDO $db) {
		$this->db = $db;
	}

	public function isValid(array $data) {
		return true;
	}
	/**
	 * returns the validation first error
	 * @return string
	 */
	public function getValidationError() {
		return $this->error;
	}
}