<?php
/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2011 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nellacms.com
 */

namespace NellaTests\Models;

/**
 * @document
 */
class VersionableDocumentMock extends \Nella\Models\Document implements \Nella\Models\IVersionable
{
	/** @string */
	private $data;
	
	/**
	 * @return string
	 */
	public function getData()
	{
		return $this->data;
	}
	
	/**
	 * @param string
	 * @return VersionableDocumentMock
	 */
	public function setData($data)
	{
		$data = trim($data);
		$this->data = $data == "" ? NULL : $data;
		return $this;
	}
	
	public function takeSnapshot()
	{
		return serialize(array('data' => $this->data));
	}
}