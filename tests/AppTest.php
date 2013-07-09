<?php

class AppTest extends PHPUnit_Framework_TestCase
{
	public $app;

	public function setUp()
	{
		parent::setUp();

		/** Mock the Environment (taken from the Slim tests...) **/
		\Slim\Environment::mock(array(
			'SCRIPT_NAME' => '/foo', //<-- Physical
			'PATH_INFO' => '/bar', //<-- Virtual
			'QUERY_STRING' => 'one=foo&two=bar',
			'SERVER_NAME' => 'slimframework.com',
		));

		$this->app = new \Bistro\Swell\App(array(
			'web.path' => '/subdirectory'
		));
	}

	public function testWebPath()
	{
		$this->assertSame('/subdirectory', $this->app->getWebPath());
	}

	public function testRestfulRouting()
	{
		$routes = $this->app->rest('/user', new \Bistro\Swell\Controller\Rest($this));

		$this->assertSame('/user(/:id)', $routes['get']->getPattern());
		$this->assertSame('/user', $routes['post']->getPattern());
		$this->assertSame('/user/:id', $routes['put']->getPattern());
		$this->assertSame('/user/:id', $routes['delete']->getPattern());
	}

	public function testRestfulRoutingWithMiddleware()
	{
		$m_one = function(){};
		$m_two = function(){};

		$routes = $this->app->rest('/user', $m_one, $m_two, new \Bistro\Swell\Controller\Rest($this));
		$this->assertSame(2, count($routes['get']->getMiddleware()));
	}

}
