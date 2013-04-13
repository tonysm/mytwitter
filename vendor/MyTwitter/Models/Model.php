<?php

namespace MyTwitter\Models;

class Model {
	protected $db;
	protected $tabela;
	
	public function __construct(\PDO $db) {
		$this->db = $db;
	}

	public function isValid(array $data) {
		return true;
	}
}