<?php

namespace Bistro\Swell;

class App extends \Slim\Slim
{
	/**
	 * {@inheritDoc}
	 */
	public function redirect($url, $status = 302)
	{
		parent::redirect($this->webPath().$url, $status);
	}

	/**
	 * @return string  The web path (useful for applications in sub-directories)
	 */
	public function webPath()
	{
		$path = '';

		if (isset($this->settings['web.path']))
		{
			$path = $this->settings['web.path'];
		}

		return $path;
	}

}
