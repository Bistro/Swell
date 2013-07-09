<?php

class MustacheTest extends PHPUnit_Framework_TestCase
{
	public $view;
	public $directory;

	public function setUp()
	{
		parent::setUp();

		$this->directory = __DIR__.DIRECTORY_SEPARATOR."views";

		$this->view = new \Bistro\Swell\View\Mustache;
		$this->view->setTemplatesDirectory($this->directory);
	}

	public function testEngineIsCreated()
	{
		$this->assertInstanceOf('Mustache_Engine', $this->view->engine);
	}

	public function testOutput()
	{
		$expected = \file_get_contents($this->directory.'/test.html');
		$this->view->appendData(array(
			'title' => "Welcome",
			'name' => "Dave"
		));

		$this->assertSame($expected, $this->view->render('test'));
	}

}
