<?php

namespace App\Controllers;

use App\View\Helpers\Hashtag;

class Messages extends AppController
{
	public function post_index()
	{
		if ( ! $this->isAllowed())
        {
			return $this->redirect("/");
		}

		$message = $this->request->getData('message');
		$user = $this->Session->getUser();

		$message['user_id'] = $user['id'];
		
		$this->Message = $this->loadModel("Message");

		if ($this->Message->isValid( $message )) {
			if( $this->Message->save($message) ) {
				$this->Session->writeMessage("Mensagem publicada com sucesso!", "success");
			} else {
				$this->Session->writeMessage("Ocorreu um erro ao tentar publicar essa mensagem", "error");
			}
			
		} else {
			$this->Session->writeMessage($this->Message->getValidationError(), "error");
		}

		return $this->redirect("/Users");
	}

	public function get_find()
	{
		if (!$this->isAllowed()) {
			return $this->redirect("/users");
		}

		$param = $this->request->getParam('hash');
		$page = (int) $this->request->getData('pg');

		if (!empty($param)) {
			$this->Message = $this->loadModel("Message");
			$messages = $this->Message->findByHashTag( $param, $page );
			$this->set("Hashtag", new Hashtag());
			$this->set("messages", $messages);
		}
		$this->set("term", $param);
		$this->render("messages/find");
	}

	public function post_find()
	{
		if (!$this->isAllowed()) {
			return $this->redirect("/users");
		}
		$data = $this->request->getData('find');
		if (!empty($data['term'])) {
			$this->Message = $this->loadModel("Message");
			$messages = $this->Message->findByHashTag( $data['term'] );
		} else {
			$this->Session->writeMessage("Não entendemos o que você está procurando");
			$messages = array();
		}
		$this->set("term", $data['term']);
		$this->set("Hashtag", new Hashtag());
		$this->set('messages', $messages);
		$this->render("messages/find");
	}
}