<?php

namespace App\Models;

class Message extends AppModel {
	protected $tabela = "messages";
	protected $tabela_users = "users";
	protected $tabela_friends = "users_friends";
	protected $error = "";

	public function isValid(array $data)
	{
		if (!isset($data['text']) || empty($data['text'])) {
			$this->error = "Mensagem não pode ser publicada em branco";
			return false;
		}
		$data['text'] = strip_tags($data['text']);
		$data['user_id'] = isset($data['user_id']) ? (int) $data['user_id'] : '';
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
			if (!$this->isValid($message))
				throw new \Exception("Mensagem inválida");
				
			$sql = "INSERT INTO {$this->tabela} (text, user_id, created_at) VALUES (:text, :user_id, SYSDATE());";

			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":text", strip_tags($message['text']));
			$stmt->bindValue(":user_id", $message['user_id']);
			return $stmt->execute();
		} catch (\Exception $e) {
			
		}
		return false;
	}

	public function findByUserId($user_id)
	{
		try {
			$sql = "SELECT
						m.text, m.user_id, m.created_at
					FROM
						{$this->tabela} m
					WHERE
						m.user_id = :user_id
					ORDER BY
						m.created_at DESC";

			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":user_id", $user_id);
			$stmt->execute();

			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		} catch (\PDOException $e) {
			
		}

		return array();
	}

	public function findByUserIdAndFriends( $user_id, array $friends, $page = false )
	{
		$limit = 8;
		if ($page !== false) {
			$page = $page == 0 ? 1 : $page;
			$start = (($page - 1) * $limit);
		} else {
			$start = 0;
		}
		try {
			// creating the (?,?,?) to put as IN ()
			$count_friends = count($friends);
			if($count_friends) {
				$in_query = implode(",", array_fill(1, $count_friends, "?"));
				$in_query = " OR m.user_id IN ({$in_query}) ";
			} else {
				$in_query = "";
			}
			$sql = "SELECT 
						m.id, m.text, m.user_id, m.created_at, u.login
					FROM
						{$this->tabela} m
					LEFT JOIN
						{$this->tabela_users} u ON (m.user_id = u.id)
					WHERE
						m.user_id = ? {$in_query}
					ORDER BY
						m.created_at DESC
					LIMIT {$start}, {$limit}";
			$stmt = $this->db->prepare( $sql );
			$stmt->bindValue(1, $user_id);
			if ($count_friends) {
				foreach($friends as $index => $id) {
					$stmt->bindValue(($index + 2), $id);
				}
			}
			$stmt->execute();

			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		} catch (\PDOException $e) {
			
		}

		return array();
	}

	public function findByHashTag( $term , $page = false )
	{
		$limit = 5;
		if ($page !== false) {
			$page = $page == 0 ? 1 : $page;
			$start = (($page - 1) * $limit);
		} else {
			$start = 0;
		}
		$term = str_replace("#", "", $term);
		try {
			$sql = "SELECT 
						m.id, m.text, m.user_id, m.created_at, u.login
					FROM
						{$this->tabela} m
					LEFT JOIN
						{$this->tabela_users} u ON (m.user_id = u.id)
					WHERE
						LOWER(m.text) LIKE LOWER(:term)
					ORDER BY
						m.created_at DESC
					LIMIT {$start}, {$limit}";
			$stmt = $this->db->prepare( $sql );
			$stmt->bindValue(":term", "%#{$term}%");
			$stmt->execute();

			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		} catch (\PDOException $e) {
			
		}

		return array();
	}
}