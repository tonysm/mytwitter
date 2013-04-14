<?php

namespace App\Controllers\Api;

class Users extends AppController {
	public function get_index()
	{
		$id = $this->request->getParam('id');

		if (!$id) {
			$this->setStatusCode(400);
			$this->set("message", "Nenhum ID foi passado");
			return $this->render("api/error");
		}

		$this->User = $this->loadModel("user");

		if (!$this->User->exists($id)) {
			$this->setStatusCode(404);
			$this->set("message", "Not found");
			return $this->render("api/error");
		}
		$this->Message = $this->loadModel("Message");
		$messages = $this->Message->findById( $id );
		
		$this->set("data", $messages);
		$this->render("api/result");
	}
}