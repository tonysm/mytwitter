<?php

namespace App\Controllers;

class Users extends AppController
{
	/**
	 * add a new user
	 * @return void
	 */
	public function post_index()
	{
		$this->User = $this->loadModel('User');
		$data = $this->request->getData('user');
		// checks if the data is valid
		if ($this->User->isValid( $data )) {
			// if it is, try to save it
			if ($this->User->save( $data ) ) {
				// if save it, send him to his home
				$this->Session->write("message", "Seja bem-vindo ao MyTwitter");
				$this->Session->write("message-class", "success");
				return $this->redirect("Users/index");
			} else {
				// if cannot save it, send a message
				$this->Session->write("message", "Não conseguiu salvar");
				$this->Session->write("message-class", "error");
				return $this->redirect("/");
			}
		}
		// if invalid, send a message
		$this->Session->write("message", $this->User->getValidationError());
		$this->Session->write("message-class", "error");
		return $this->redirect("/");
	}

	public function get_index()
	{
		print_r($this->Session);
	}
}