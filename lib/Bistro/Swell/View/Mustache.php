<?php

namespace Bistro\Swell\View;

class Mustache extends \Slim\View
{
	public $engine;

	/**
	 * @param \Mustache_Engine $engine  The mustache engine to use for rendering
	 */
	public function __construct($engine = null)
	{
		if ($engine === null)
		{
			$engine = new \Mustache_Engine;
		}

		$this->engine = $engine;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setTemplatesDirectory($dir)
	{
		parent::setTemplatesDirectory($dir);

		$this->engine->setLoader(new \Mustache_Loader_FilesystemLoader($this->getTemplatesDirectory()));
		$this->engine->setPartialsLoader(new \Mustache_Loader_FilesystemLoader($this->getTemplatesDirectory()."/partials"));
	}

	/**
	 * @param  string $template The template name
	 * @return string           The rendered template
	 */
	public function render($template)
	{
        return $this->engine->render($template, $this->data);
	}

}
