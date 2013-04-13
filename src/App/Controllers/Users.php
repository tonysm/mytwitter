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
				die('Salvou');
			} else {
				// if cannot save it, send a message
				die('Não Salvou');
			}
		}
		// if invalid, send a message
		die($this->User->getValidationError());
	}
}