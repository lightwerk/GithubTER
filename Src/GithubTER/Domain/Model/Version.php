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
 * Version of an extension
 *
 * @author    Philipp Bergsmann <p.bergsmann@opendo.at>
 */

namespace GithubTER\Domain\Model;

class Version {

	/**
	 * @var string
	 */
	protected $number;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var version
	 */
	protected $state;

	/**
	 * @var string
	 */
	protected $reviewState;

	/**
	 * @var string
	 */
	protected $category;

	/**
	 * @var integer
	 */
	protected $downloadcounter;

	/**
	 * @var int
	 */
	protected $uploadDate;

	/**
	 * @var string
	 */
	protected $uploadComment;

	/**
	 * @var array
	 */
	protected $dependencies;


	/**
	 * @var Author
	 */
	protected $author;

	/**
	 * @var string
	 */
	protected $t3xFileMd5;


	/**
	 * @param \GithubTER\Domain\Model\Author $author
	 */
	public function setAuthor($author) {
		$this->author = $author;
	}

	/**
	 * @return \GithubTER\Domain\Model\Author
	 */
	public function getAuthor() {
		return $this->author;
	}

	/**
	 * @param string $number
	 */
	public function setNumber($number) {
		$this->number = $number;
	}

	/**
	 * @return string
	 */
	public function getNumber() {
		return $this->number;
	}

	/**
	 * @param \GithubTER\Domain\Model\Version $state
	 */
	public function setState($state) {
		$this->state = $state;
	}

	/**
	 * @return \GithubTER\Domain\Model\Version
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @param string $uploadComment
	 */
	public function setUploadComment($uploadComment) {
		$this->uploadComment = $uploadComment;
	}

	/**
	 * @return string
	 */
	public function getUploadComment() {
		return $this->uploadComment;
	}

	/**
	 * @param int $uploadDate
	 */
	public function setUploadDate($uploadDate) {
		$this->uploadDate = $uploadDate;
	}

	/**
	 * @return int
	 */
	public function getUploadDate() {
		return $this->uploadDate;
	}

	/**
	 * @param array $dependencies
	 */
	public function setDependencies($dependencies) {
		if (is_string($dependencies)) {
			$dependencies = unserialize($dependencies);
			if (!is_array($dependencies)) {
				$dependencies = array();
			}
		}
		$this->dependencies = $dependencies;
	}

	/**
	 * @return array
	 */
	public function getDependencies() {
		return $this->dependencies;
	}

	/**
	 * @param string $category
	 */
	public function setCategory($category) {
		$this->category = $category;
	}

	/**
	 * @return string
	 */
	public function getCategory() {
		return $this->category;
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
	 * @param int $downloadcounter
	 */
	public function setDownloadcounter($downloadcounter) {
		$this->downloadcounter = $downloadcounter;
	}

	/**
	 * @return int
	 */
	public function getDownloadcounter() {
		return $this->downloadcounter;
	}

	/**
	 * @param string $reviewState
	 */
	public function setReviewState($reviewState) {
		$this->reviewState = $reviewState;
	}

	/**
	 * @return string
	 */
	public function getReviewState() {
		return $this->reviewState;
	}

	/**
	 * @param string $t3xFileMd5
	 */
	public function setT3xFileMd5($t3xFileMd5) {
		$this->t3xFileMd5 = $t3xFileMd5;
	}

	/**
	 * @return string
	 */
	public function getT3xFileMd5() {
		return $this->t3xFileMd5;
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

}

?>