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
 * Mapps the extensions-XML-file to domain-objects
 *
 * @author Philipp Bergsmann <p.bergsmann@opendo.at>
 * @author Georg Ringer <typo3@ringerge.org>
 * @package GithubTER
 */
namespace GithubTER\Mapper;

use GithubTER\Exception;
use GithubTER\Domain\Model;

class ExtensionMapper extends XmlMapper {
	/**
	 * @var int
	 */
	protected $fromDate = 0;

	/**
	 * @var int
	 */
	protected $toDate = 991352658149;

	protected $allowedExtensions = array();

	/**
	 * @param string $path Loads a extensions.xml
	 * @throws \GithubTER\Exception\InvalidPathException
	 * @return void
	 */
	public function loadExtensionList($path) {
		if (file_exists($path) === FALSE) {
			throw new Exception\InvalidPathException('"' . $path . '" is not a valid filepath.', 1352644296);
		}

		$this->loadXml(file_get_contents($path));
	}

	/**
	 * @param string $extensionList
	 */
	public function run($extensionList = '') {
		$this->allowedExtensions = explode(',', $extensionList);

		$this->mappedResult = new \SplObjectStorage();
		$extensions = $this->simpleXmlElement;

		foreach ($extensions->extension as $extension) {
			$key = (string)$extension['extensionkey'];

			if ($this->extensionExistsAsArgument($key)) {
				$extensionObj = new Model\Extension();
				$extensionObj->setKey($key);

				foreach ($extension->version as $version) {
					if (
						(int)$version->lastuploaddate >= $this->getFromDate()
						&& (int)$version->lastuploaddate <= $this->getToDate()
					) {
						$authorObj = $this->mapAuthor(new Model\Author(), $version);

						/** @var $versionObj \GithubTER\Domain\Model\Version */
						$versionObj = $this->mapVersion(new Model\Version(), $version);
						$versionObj->setAuthor($authorObj);
						$versionObj->setDependencies((string)$version->dependencies);
						$versionObj->setDescription((string)$version->description);
						$versionObj->setState((string)$version->state);
						$versionObj->setReviewState((string)$version->reviewstate);
						$versionObj->setCategory((string)$version->category);
						$versionObj->setDownloadcounter((string)$version->downloadcounter);
						$versionObj->setUploadComment((string)$version->uploadcomment);
						$versionObj->setT3xFileMd5((string)$version->t3xfilemd5);


						$extensionObj->setTitle((string)$version->title);
						$extensionObj->setDescription((string)$version->description);
						$extensionObj->setState((string)$version->state);
						$extensionObj->setLastModified((int)$version->lastuploaddate);
						$extensionObj->addVersion($versionObj);
					}
				}
				$extensionObj->addAuthor($authorObj);

				if (count($extensionObj->getVersions()) > 0) {
					$this->mappedResult->attach($extensionObj);
				}
			}
		}
	}

	/**
	 * Maps the Author-Object from a Version-XML-Object
	 *
	 * @param \GithubTER\Domain\Model\Author $authorObj
	 * @param $version
	 * @return \GithubTER\Domain\Model\Author
	 */
	protected function mapAuthor(Model\Author $authorObj, $version) {
		$authorObj->setEmail((string)$version->authoremail);
		$authorObj->setName((string)$version->authorname);
		$authorObj->setUsername((string)$version->ownerusername);
		$authorObj->setCompany((string)$version->authorcompany);

		return $authorObj;
	}

	/**
	 * Maps a Version-Object from a Version-XML-Object
	 *
	 * @param \GithubTER\Domain\Model\Version $versionObj
	 * @param $version
	 * @return \GithubTER\Domain\Model\Version
	 */
	protected function mapVersion(Model\Version $versionObj, $version) {
		$versionObj->setNumber((string)$version['version']);
		$versionObj->setState((string)$version->state);
		$versionObj->setUploadComment((string)$version->uploadcomment);
		$versionObj->setUploadDate((int)$version->lastuploaddate);

		return $versionObj;
	}

	/**
	 *
	 * @param $extension
	 * @return bool
	 */
	protected function extensionExistsAsArgument($extension) {
		if (count($this->allowedExtensions) === 0) {
			return TRUE;
		}
		if (in_array($extension, $this->allowedExtensions)) {
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * @param int $fromDate
	 */
	public function setFromDate($fromDate) {
		$this->fromDate = $fromDate;
	}

	/**
	 * @return int
	 */
	public function getFromDate() {
		return $this->fromDate;
	}

	/**
	 * @param int $toDate
	 */
	public function setToDate($toDate) {
		$this->toDate = $toDate;
	}

	/**
	 * @return int
	 */
	public function getToDate() {
		return $this->toDate;
	}
}

?>