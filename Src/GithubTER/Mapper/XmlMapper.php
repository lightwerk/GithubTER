<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Philipp Bergsmann <p.bergsmann@opendo.at>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


/**
 * Absrtract class for simple XML-functions
 *
 * @author Philipp Bergsmann <p.bergsmann@opendo.at>
 * @package GithubTER
 */
namespace GithubTER\Mapper;

abstract class XmlMapper {
	/**
	 * @var \SimpleXMLElement
	 */
	protected $simpleXmlElement;

	/**
	 * @var \SplObjectStorage
	 */
	protected $mappedResult;

	/**
	 * @param string $xml
	 */
	public function loadXml($xml) {
		$this->simpleXmlElement = new \SimpleXMLElement($xml);
	}

	/**
	 * @return \SplObjectStorage
	 */
	public function getMappedResult() {
		return $this->mappedResult;
	}
}
?>