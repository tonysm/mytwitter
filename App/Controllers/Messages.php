<?php

namespace App\Controllers;

use App\View\Helpers\Hashtag;

class Messages extends AppController {
	public function post_index()
	{
		if (!$this->isAllowed()) {
			return $this->redirect("/");
		}

		$message = $this->request->getData('message');
		$user = $this->Session->getUser();

		$message['user_id'] = $user['id'];
		
		$this->Message = $this->loadModel("Message");

		if ($this->Message->isValid( $message )) {
			if( $this->Message->save($message) ) {
				$this->Session->write("message", "Mensagem publicada com sucesso!");
				$this->Session->write("message-class", "success");
			} else {
				$this->Session->write("message", "Ocorreu um erro ao tentar publicar essa mensagem");
				$this->Session->write("message-class", "error");
			}
			
		} else {
			$this->Session->write("message", $this->Message->getValidationError());
			$this->Session->write("message-class", "error");
		}
		return $this->redirect("/Users");
	}

	public function get_find()
	{
		if (!$this->isAllowed()) {
			return $this->redirect("/users");
		}

		$param = $this->request->getParam('hash');

		if (!empty($param)) {
			$this->Message = $this->loadModel("Message");
			$messages = $this->Message->findByHashTag( $param );
			$this->set("Hashtag", new Hashtag());
			$this->set("messages", $messages);
		}

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
			$messages = $this->Message->findByHashTag( $term );
		} else {
			$this->Session->write("message", "Não entendemos o que você está procurando");
			$messages = array();
		}
		$this->set("Hashtag", new Hashtag());
		$this->set('messages', $messages);
		$this->render("messages/find");
	}
}