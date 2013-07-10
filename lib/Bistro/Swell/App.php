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

		$get = array_merge(array($name.'(/:id)'), $args, (array) function($id) use ($controller){
			$controller->execute('get', $id);
		});

		$post = array_merge(array($name), $args, (array) function() use ($controller){
			$controller->execute('post');
		});

		$put = array_merge(array($name."/:id"), $args, (array) function($id) use ($controller){
			$controller->execute('put', $id);
		});

		$delete = array_merge(array($name."/:id"), $args, (array) function($id) use ($controller){
			$controller->execute('delete', $id);
		});

		$routes = array();
		$routes['get'] = \call_user_func_array(array($this, 'get'), $get);
		$routes['post'] = \call_user_func_array(array($this, 'post'), $post);
		$routes['put'] = \call_user_func_array(array($this, 'put'), $put);
		$routes['delete'] = \call_user_func_array(array($this, 'delete'), $delete);

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
			$db = $this->settings('database');
			$pdo = new \PDO("mysql:dbname={$db['database']};host={$db['host']}", $db['user'], $db['password']);
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
