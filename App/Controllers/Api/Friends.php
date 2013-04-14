<?php

namespace App\Controllers\Api;

class Friends extends AppController {
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
		$friends = $this->User->findFriends( $id );
		
		$this->set("data", $friends);
		$this->render("api/result");
	}
}