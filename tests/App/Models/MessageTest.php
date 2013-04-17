<?php

class PDOMock extends PDO {
	public function __construct()
	{}
}

class MessageTest extends PHPUnit_Framework_TestCase {
	public $Model;

	public function setUp()
	{
		$pdoMock = $this->getMock("PDOMock", array("fetchAll", "fetch", "prepare"));
		$this->Model = new App\Models\Message( $pdoMock );
	}

	public function testClassType()
	{
		$this->assertInstanceOf("App\\Models\\Message", $this->Model);
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
}