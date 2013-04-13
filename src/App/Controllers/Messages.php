<?php

namespace App\Controllers;

class Messages extends AppController {
	public function post_index()
	{
		if (!$this->isAllowed()) {
			return $this->redirect("/");
		}

		$message = $this->request->getData('message');
		$user = $this->Session->getUser();
		$message['user_id'] = $user['id'];
		// strip_tags
		$message['text'] = strip_tags($message['text']);
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
}