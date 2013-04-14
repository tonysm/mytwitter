<?php

namespace App\Controllers\Api;

class Users extends AppController {
	/**
	 * @route /users/:id.json
	 * @return void
	 */
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
		$user = $this->User->findById( $id );
		
		$this->set("data", $user);
		$this->render("api/result");
	}
	/**
	 * @route /users/:id/messages.json
	 * @return void
	 */
	public function get_messages()
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
		$messages = $this->Message->findByUserId( $id );
		
		$this->set("data", $messages);
		$this->render("api/list");
	}
	/**
	 * route: /users/:id/friends.json
	 * @return void
	 */
	public function get_friends()
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
		$this->render("api/list");
	}
}