<?php

namespace Bistro\Swell;

class App extends \Slim\Slim
{
	/**
	 * @var \Bistro\Data\Adapter\PDO
	 */
	protected $pdo = null;

	/**
	 * An easy hook for restful resources.
	 *
	 * It takes the same arguments as a call to any Slim Routing.
	 *
	 * @return array  Routess for [get, post, put, delete]
	 */
	public function rest()
	{
		$args = \func_get_args();
		$name = \array_shift($args);
		$controller = \array_pop($args);

		$routes = array(
			'get' => $this->router->map("{$name}(/:id)", function($id) use ($controller){
				$controller->execute('get', $id);
			})->via(\Slim\Http\Request::METHOD_GET),

			'post' => $this->router->map($name, function() use ($controller){
				$controller->execute('post');
			})->via(\Slim\Http\Request::METHOD_POST),

			'put' => $this->router->map("{$name}/:id", function($id) use ($controller){
				$controller->execute('put', $id);
			})->via(\Slim\Http\Request::METHOD_PUT),

			'delete' => $this->router->map("{$name}/:id", function($id) use ($controller){
				$controller->execute('delete', $id);
			})->via(\Slim\Http\Request::METHOD_DELETE),
		);

		if (count($args) > 0)
		{
			foreach ($routes as $route)
			{
				$route->setMiddleware($args);
			}
		}

		return $routes;
	}

	/**
	 * {@inheritDoc}
	 */
	public function redirect($url, $status = 302)
	{
		parent::redirect($this->getWebPath().$url, $status);
	}

	/**
	 * @return string  The web path (useful for applications in sub-directories)
	 */
	public function getWebPath()
	{
		$path = '';

		if (isset($this->settings['web.path']))
		{
			$path = $this->settings['web.path'];
		}

		return $path;
	}

	/**
	 * @return \Bistro\Data\Adapter\PDO  The PDO adapter instance used for data.
	 */
	public function pdo()
	{
		if ($this->pdo === null)
		{
			$pdo = new \PDO($this->config('database.dsn'), $this->config('database.user'), $this->config('database.password'));
			$this->pdo = new \Bistro\Data\Adapter\PDO($pdo);
		}

		return $this->pdo;
	}

	/**
	 * @see    \Bistro\Data\Table
	 *
	 * @param  string $name    The class name of the table
	 * @param  array  $options Any additional options for the table class
	 * @return \Bistro\Data\Table
	 */
	public function table($name, $options = array())
	{
		return new $name($this->pdo(), $options);
	}

}
