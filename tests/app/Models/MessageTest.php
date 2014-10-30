<?php

use MyTwitter\Di\Container;

class MessageTest extends PHPUnit_Framework_TestCase {
	public $Model;

	public function setUp()
	{
		$this->Model = Container::getModelToTest('Message');

		// recreates the database the database
		$this->_buildDb();
		// inserts users
		$this->_insertUsers();
	}

	public function tearDown()
	{
		$this->Model = null;
		$this->_dropDb();
	}

	private function _dropDb() {
		$db = Container::getDb('test');
		$db->query("DROP TABLE messages;");
		$db->query("DROP TABLE users_friends;");
		$db->query("DROP TABLE users;");
	}

	public function _buildDb()
	{
		$db = Container::getDb('test');
		$schema = file_get_contents(CORE_DIR . "schema.sql");
		$db->query( $schema );
	}

	public function _insertUsers()
	{
		$db = Container::getDb('test');
		$db->query("INSERT INTO users (nome, login, senha) VALUES ('lorem ipsum 1', 'lorem1', 'lasdmklasmd'), ('lorem ipsum 2', 'lorem2', 'lasdmklasmd')");
	}

	public function testClassType()
	{
		$this->assertInstanceOf("app\\Models\\Message", $this->Model);
	}

	public function validMessagesDP()
	{
		return [
			[['text' => 'lorem ipsum', 'user_id' => 1]],
			[['text' => 'lorem ipsum 2', 'user_id' => 17]]
		];
	}
	/**
	 * @dataProvider validMessagesDp
	 */
	public function testValidatesMessageCorrectly( $message )
	{
		$this->assertTrue($this->Model->isValid( $message ), 'Não está validando mensagens corretas');
	}

	public function messagesWithoutTextDP()
	{
		return [
			[['user_id' => 1]],
			[['user_id' => 1, 'text' => '']],
			[['user_id' => 1, 'text' => null]]
		];
	}
	/**
	 * @dataProvider messagesWithoutTextDP
	 */
	public function testInvalidatesMessageWithoutText( $message )
	{
		$this->assertFalse( $this->Model->isValid( $message ), 'Está validando mensagem sem texto' );
	}

	public function messagesWithoutUserId()
	{
		return [
			[['text' => 'lorem ipsum']],
			[['text' => 'lorem ipsum', 'user_id' => '']],
			[['text' => 'lorem ipsum', 'user_id' => null]],
			[['text' => 'lorem ipsum', 'user_id' => 'lorem']],
		];
	}
	/**
	 * @dataProvider messagesWithoutUserId
	 */
	public function testInvalidatesMessageWithoutUserId( $message )
	{
		$this->assertFalse( $this->Model->isValid( $message ), 'Está validando mensagem sem associação com usuário' );
	}

	public function testInvalidatesMessageWhenTextHasMoreThen140Characters()
	{
		$message = array('user_id' => 1, 'text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officiis, praesentium, quas, sequi, labore fuga fugit officia dolor minus saepe nesciunt deserunt ullam aliquid eos incidunt accusamus voluptas culpa qui reprehenderit.');
		$this->assertFalse( $this->Model->isValid( $message ) , 'Está validando mensagem com mais de 140 caracteres');
	}

	public function testSavesMessageCorrectly()
	{
		$message = array(
			'text' => 'lorem ipsum dolor',
			'user_id' => 1
		);
		$this->assertTrue( $this->Model->save( $message ) );
	}
	
	public function testFailsWhenSavingInvalidMessage()
	{
		$message = array();
		$this->assertFalse($this->Model->save( $message ));
	}

	public function testShouldFetchMessagesCorrectlyByUserId()
	{
		$message = array(
			array(
				'text' => 'lorem ipsum 1',
				'user_id' => 1
			),
			array(
				'text' => 'lorem ipsum 2',
				'user_id' => 2
			),
			array(
				'text' => 'lorem ipsum 3',
				'user_id' => 1
			)
		);

		$this->Model->save($message[0]);
		$this->Model->save($message[1]);
		$this->Model->save($message[2]);

		$db_messages = $this->Model->findByUserId(1);

		$this->assertEquals(2, count($db_messages), 'Não está filtrando corretamente por usuário');
	}

	public function testShouldFailFetchingMessagesByUserIdWithoutUserId()
	{
		$message = array(
			array(
				'text' => 'lorem ipsum 1',
				'user_id' => 1
			),
			array(
				'text' => 'lorem ipsum 2',
				'user_id' => 2
			),
			array(
				'text' => 'lorem ipsum 3',
				'user_id' => 1
			)
		);

		$this->Model->save($message[0]);
		$this->Model->save($message[1]);
		$this->Model->save($message[2]);

		$db_messages = $this->Model->findByUserId(0);

		$this->assertEquals(0, count($db_messages), 'Está trazendo mensagens sem passar user_id');
	}

}