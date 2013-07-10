<?php

namespace Bistro\Swell\Controller;

class Rest extends \Bistro\Swell\Controller
{
	/**
	 * @var Bistro\Data\Table
	 */
	protected $table;

	/**
	 * @param string            $name The name of the table to use for data access
	 * @param \Bistro\Swell\App $app  The application
	 */
	public function __construct($name, $app)
	{
		$this->table = $app->table($name);
		parent::__construct($app);
	}

	/**
	 * Make sure the response is in the correct json friendly format
	 */
	protected function after()
	{
		$response = $this->app->response();
		$response['Content-Type'] = "application/json";

		if ($this->content !== null)
		{
			$response->body(\json_encode($this->content));
		}
	}

	/**
	 * GET request (grab data)
	 *
	 * @param int $id  The record id (if getting 1 record)
	 */
	public function get($id = null)
	{
		if ($id === null)
		{
			$this->content = array('result' => $this->table->all()->toArray());
		}
		else
		{
			$this->content = array('result' => $this->table->get($id)->toArray());
		}
	}

	/**
	 * POST request (create record)
	 */
	public function post()
	{
		$data = json_decode($this->app->request()->getBody(), true);
		$model = new $this->table->model($data);

		$errors = $model->validate();

		$response = $this->app->response();

		if ( ! empty($errors))
		{
			$response->status(400);
			$this->content = array('errors' => $errors);
		}
		else
		{
			$this->table->save($model);
			$this->content = $model->toArray();
			$response->status(201);
		}
	}

	/**
	 * PUT request (update record)
	 *
	 * @param int $id  The record id
	 */
	public function put($id)
	{
		$data = json_decode($this->app->request()->getBody(), true);

		$model = $this->table->get($id);
		$model->set($data);

		$errors = $model->validate();

		$response = $this->app->response();

		if ( ! empty($errors))
		{
			$response->status(400);
			$this->content = array('errors' => $errors);
		}
		else
		{
			$this->table->save($model);
			$this->content = $model->toArray();
		}
	}

	/**
	 * DELETE request (delete record)
	 *
	 * @param int $id  The record id
	 */
	public function delete($id)
	{
		$model = $this->table->get($id);
		$this->table->delete($model);

		$this->app->response()->status(204);
	}

}
