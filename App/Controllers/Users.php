<?php

namespace App\Controllers;

use App\View\Helpers\Hashtag;

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
				$user = $this->User->findByLoginAndSenha($data['login'], $data['senha']);
				$this->Session->authenticate($user);
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
		$this->Message = $this->loadModel("Message");
		$this->User = $this->loadModel("User");

		$user = $this->Session->getUser();
		$friends = $this->User->findFriendsIds( $user['id'] );
		$messages = $this->Message->findByUserIdAndFriends( $user['id'], $friends );

		$this->set("Hashtag", new Hashtag());
		$this->set("messages", $messages);
		$this->render('users/index');
	}
	/**
	 * user login method
	 * @return void
	 */
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
	/**
	 * user logout method
	 * @return void
	 */
	public function get_logout()
	{
		if($this->isAllowed()) {
			$this->Session->logout();
			$this->Session->write("message", "Volte sempre");
			$this->Session->write("message-class", "info");
		}
		return $this->redirect("/");
	}
	/**
	 * action get_find
	 * @return void
	 */
	public function get_find()
	{
		if(!$this->isAllowed()){
			return $this->redirect("/");
		}

		$this->render("users/find");
	}
	/**
	 * action post_find
	 * processes the term
	 * @return 
	 */
	public function post_find()
	{
		if (!$this->isAllowed()) {
			return $this->redirect("/");
		}
		$data = $this->request->getData('find');
		$this->User = $this->loadModel("User");
		$me = $this->Session->getUser();

		$usuarios = $this->User->findByNomeOrLoginWithoutMe( $data['term'], $me['id'] );
		$friends = $this->User->findFriendsIds($me['id']);

		$this->set("usuarios", $usuarios);
		$this->set("friends", $friends);
		$this->render('users/find');
	}
	/**
	 * action to follow a friend
	 * @return void
	 */
	public function post_follow()
	{
		if (!$this->isAllowed()) {
			return $this->redirect("/");
		}

		$data = $this->request->getData('follow');
		$user = $this->Session->getUser();
		// am I trying to follow me?
		if ($data['user_id'] === $user['id'] ) {
			$this->Session->write("message", "Você não pode seguir você mesmo");
			$this->Session->write("message-class", "info");
			return $this->redirect("/Users");
		}

		$this->User = $this->loadModel("User");

		if ($this->User->follow($user['id'], $data['user_id'])) {
			$nome = $this->User->findNomeById($data['user_id']);
			$this->Session->write("message", "Agora você já está seguindo <strong>{$nome}</strong>");
			$this->Session->write("message-class", "success");
			return $this->redirect("/Users");
		}

		$this->Session->write("message", "Ocorreu um erro ao tentar seguir o usuário");
		$this->Session->write("message-class", "error");
		return $this->redirect("/Users");
	}

	public function post_unfollow()
	{
		if(!$this->isAllowed()) {
			return $this->redirect("/");
		}

		$data = $this->request->getData('unfollow');
		$user = $this->Session->getUser();

		// am I trying to unfollow me?
		if ($data['user_id'] === $user['id']) {
			$this->Session->write("message", "Você não pode deixar de seguir você mesmo");
			$this->Session->write("message-class", "info");
			return $this->redirect("/Users");
		}

		$this->User = $this->loadModel("User");

		if ($this->User->unfollow($user['id'], $data['user_id'])) {
			$nome = $this->User->findNomeById($data['user_id']);
			$this->Session->write("message", "Agora você não está seguindo <strong>{$nome}</strong>");
			$this->Session->write("message-class", "info");
			return $this->redirect("/Users");
		}

		$this->Session->write("message", "Ocorreu um erro ao tentar deixar de seguir um usuário");
		$this->Session->write("message-class", "error");
		return $this->redirect("/Users");
	}
}