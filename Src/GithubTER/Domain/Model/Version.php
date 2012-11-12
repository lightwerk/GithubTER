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
 * @author    Philipp Bergsmann <p.bergsmann@opendo.at>
 * @package $PACKAGE$
 * @subpackage $SUBPACKAGE$
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
	protected $uploadComment;

	/**
	 * @var Author
	 */
	protected $author;

	/**
	 * @var int
	 */
	protected $uploadDate;

	/**
	 * @var version
	 */
	protected $state;

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
}
?>