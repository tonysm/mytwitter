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
	/**
	 * user index page
	 * @return void
	 */
	public function get_index()
	{
		if(!$this->isAllowed()) {
			return $this->redirect("/");
		}
		$this->render('users/index');
	}

	public function post_login()
	{
		$this->User = $this->loadModel("User");
		$data = $this->request->getData('user');

		if(isset($data) && !empty($data)) {
			$user = $this->User->findByLoginAndSenha($data['login'], $data['senha']);
			if(!empty($user)) {
				$nome = explode(' ',$user['nome'])[0];
				$this->Session->authenticate($user);
				$this->Session->write("message", "Olá, {$nome}. Seja bem-vindo!");
				$this->Session->write("message-class", "success");
				return $this->redirect("/Users/index");
			}

			$this->Session->write("message", "Usuário ou senha inválidos");
			$this->Session->write("message-class", "error");
			return $this->redirect("/");
		}

		$this->Session->write("message", "Opps.. preencha o login e a senha");
		$this->Session->write("message-class", "error");
		return $this->redirect("/");
	}

	public function get_logout()
	{
		if($this->isAllowed()) {
			$this->Session->logout();
			$this->Session->write("message", "Volte sempre");
			$this->Session->write("message-class", "info");
		}
		return $this->redirect("/");
	}
}