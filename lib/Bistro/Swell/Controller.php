<?php

namespace Bistro\Swell;

class Controller
{
	protected $app;

	protected $content = null;

	/**
	 * @param \Slim\Slim $app  The slim application
	 */
	public function __construct($app)
	{
		$this->app = $app;
	}

	protected function before(){}
	protected function after(){}

	/**
	 * @return \Bistro\Swell\Controller
	 */
	public function execute($method)
	{
		$args = \func_get_args();
		\array_shift($args);

		$this->before();
		\call_user_func_array(array($this, $method), $args);
		$this->after();

		return $this;
	}

}
