<?php
/**
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * For the full copyright and license information,
 * please view the file LICENSE.txt that was distributed with this source code.
 */

namespace Nella\Media\Routes;

use Nette\Http\Url;

/**
 * File route
 *
 * @author	Pavel Kučera
 * @author	Patrik Votoček
 *
 * @property-write \Nella\Doctrine\Container
 */
class FileRoute extends \Nette\Application\Routers\Route
{
	/** @var \Nette\Application\Routes\Route */
	private $route;

	/**
	 * @param string example '/some/<file>.<ext>'
	 * @param \Nella\Media\Model\IFileDao
	 * @param \Nella\Addons\Media\IFilePresenterCallback
	 * @param string
	 */
	public function __construct($mask, \Nella\Media\Model\IFileDao $model, \Nella\Media\IFilePresenterCallback $callback, $fullSlugMask = '<file>_<ext>')
	{
		$this->route = new \Nette\Application\Routers\Route($mask, function ($file, $ext) use ($model, $callback, $fullSlugMask) {
			$fullSlug = str_replace(array('<file>', '<ext>'), array($file, $ext), $fullSlugMask);
			$fileEntity = $model->findOneByFullSlug($fullSlug);

			if (!$fileEntity) {
				throw new \Nette\Application\BadRequestException("Invalid file '$file.$ext' does not found");
			}

			return callback($callback)->invoke($fileEntity);
		});
	}

	/**
	 * Maps HTTP request to a PresenterRequest object.
	 *
	 * @param \Nette\Http\IRequest
	 * @return \Nette\Application\Request|NULL
	 * @throws \Nette\InvalidStateException
	 */
	public function match(\Nette\Http\IRequest $httpRequest)
	{
		return $this->route->match($httpRequest);
	}

	/**
	 * Constructs absolute URL from Request object.
	 *
	 * @param  \Nette\Application\Request
	 * @param  \Nette\Http\Url referential URI
	 * @return string|NULL
	 */
	public function constructUrl(\Nette\Application\Request $appRequest, Url $refUrl)
	{
		$url = $this->route->constructUrl($appRequest, $refUrl);
		if ($url != NULL) {
			if (is_string($url)) {
				$url = new Url($url);
			}
			$url->setQuery('')->canonicalize();
		}
		return $url;
	}
}

