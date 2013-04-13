<?php

namespace App\Models;

class Message extends AppModel {
	protected $tabela = "messages";
	protected $error = "";

	public function isValid(array $data)
	{
		if (!isset($data['text']) || empty($data['text'])) {
			$this->error = "Mensagem não pode ser publicada em branco";
			return false;
		}

		if (!isset($data['user_id']) || empty($data['user_id'])) {
			$this->error = "Você precisa estar logado para publicar";
			return false;
		}

		if (strlen($data['text']) > 140) {
			$this->error = "Mensagem deve ter no máximo 140 caracteres";
			return false;
		}

		return true;
	}
	public function save(array $message)
	{
		try {
			$sql = "INSERT INTO {$this->tabela} (text, user_id, created_at) VALUES (:text, :user_id, SYSDATE());";

			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":text", strip_tags($message['text']));
			$stmt->bindValue(":user_id", $message['user_id']);
			return $stmt->execute();
		} catch (\PDOException $e) {
			
		}
		return false;
	}
}