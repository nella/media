<?php
/**
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * For the full copyright and license information,
 * please view the file LICENSE.txt that was distributed with this source code.
 */

namespace Nella\Media\Callbacks;

/**
 * File presenter callback (convert request to response)
 *
 * @author	Patrik Votoček
 */
class FilePresenterCallback extends \Nette\Object implements \Nella\Media\IFilePresenterCallback
{
	/** @var \Nella\Media\IStorage */
	private $storage;

	/**
	 * @param \Nella\Media\IStorage
	 */
	public function __construct(\Nella\Media\IStorage $storage)
	{
		$this->storage = $storage;
	}

	/**
	 * @param \Nella\Media\IFile
	 * @return \Nette\Application\Responses\FileResponse
	 */
	public function __invoke(\Nella\Media\IFile $file)
	{
		$path = $this->storage->load($file);
		if (!$path) {
			throw new \Nette\Application\BadRequestException('File not found', 404);
		}

		return new \Nette\Application\Responses\FileResponse($path, pathinfo($path, PATHINFO_BASENAME), $file->getContentType());
	}
}

