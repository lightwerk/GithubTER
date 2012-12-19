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
 * $DESCRIPTION$
 *
 * @author Philipp Bergsmann <p.bergsmann@opendo.at>
 * @package GithubTER
 */
namespace GithubTER\Domain\Model;

class Extension {
	/**
	 * @var string
	 */
	protected static $TER_ZIP_PATH = 'http://typo3.org/extensions/repository/download/%1$s/%2$s/zip/';

	/**
	 * @var string
	 */
	protected $key;

	/**
	 * @var \SplObjectStorage
	 */
	protected $versions;

	/**
	 * @var int
	 */
	protected $lastModified;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $state;

	/**
	 * @var \SplObjectStorage
	 */
	protected $authors;

	/**
	 * @var string
	 */
	protected $repositoryPath;

	public function __construct() {
		$this->versions = new \SplObjectStorage();
		$this->authors = new \SplObjectStorage();
	}

	/**
	 * @param \SplObjectStorage $authors
	 */
	public function setAuthors(\SplObjectStorage $authors) {
		$this->authors = $authors;
	}

	/**
	 * @return \SplObjectStorage
	 */
	public function getAuthors() {
		return $this->authors;
	}

	/**
	 * @param Author $author
	 */
	public function addAuthor(Author $author) {
		$this->authors->attach($author);
	}

	/**
	 * @param Author $author
	 */
	public function removeAuthor(Author $author) {
		$this->authors->detach($author);
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $key
	 */
	public function setKey($key) {
		$this->key = $key;
	}

	/**
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @param int $lastModified
	 */
	public function setLastModified($lastModified) {
		$this->lastModified = $lastModified;
	}

	/**
	 * @return int
	 */
	public function getLastModified() {
		return $this->lastModified;
	}

	/**
	 * @param string $state
	 */
	public function setState($state) {
		$this->state = $state;
	}

	/**
	 * @return string
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param \SplObjectStorage $versions
	 */
	public function setVersions($versions) {
		$this->versions = $versions;
	}

	/**
	 * @return \SplObjectStorage
	 */
	public function getVersions() {
		return $this->versions;
	}

	/**
	 * @param Version $version
	 */
	public function addVersion(Version $version) {
		$this->versions->attach($version);
	}

	/**
	 * @param Version $version
	 */
	public function removeVersion (Version $version) {
		$this->versions->detach($version);
	}

	/**
	 * @param Version $version
	 * @return string
	 */
	public function getZipURL(Version $version) {
		return sprintf(self::$TER_ZIP_PATH, $this->getKey(), $version->getNumber());
	}

	/**
	 * @param string $repositoryPath
	 */
	public function setRepositoryPath($repositoryPath) {
		$this->repositoryPath = $repositoryPath;
	}

	/**
	 * @return string
	 */
	public function getRepositoryPath() {
		return $this->repositoryPath;
	}
}
?>