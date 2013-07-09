<?php

namespace Bistro\Swell\Controller;

class Rest extends \Bistro\Swell\Controller
{
	protected function after()
	{
		$response = $this->app->response();
		$response['Content-Type'] = "application/json";

		if ($this->content !== null)
		{
			$response->body(\json_encode($this->content));
		}
	}

	public function get($id = null)
	{

	}

	public function post()
	{

	}

	public function put($id)
	{

	}

	public function delete($id)
	{
		$this->app->response()->status(204);
	}

}