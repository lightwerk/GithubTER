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
namespace GithubTER\Service;

class T3xExtractor {
	/**
	 * @var string
	 */
	protected $t3xFileName;

	/**
	 * @var
	 */
	protected $fileContent;

	/**
	 * @var array
	 */
	protected $directories;

	/**
	 * @var array
	 */
	protected $files;

	/**
	 * @var string
	 */
	protected $extensionDir;

	public function extract() {

		$this->fileContent = file_get_contents($this->t3xFileName);
		$this->parseFileParts();
		$this->directories = $this->extractDirectoriesFromExtensionData();
		$this->createDirectoriesForExtensionFiles();
		$this->writeExtensionFiles();
	}

	/**
	 * Explodes the parts of the T3X-file and uncompresses the content
	 *
	 * @return void
	 */
	protected function parseFileParts() {
		$this->fileContent = explode(':', $this->fileContent, 3);
		if ($this->fileContent[1] === 'gzcompress') {
			$this->fileContent[2] = gzuncompress($this->fileContent[2]);
		}
		$this->files = unserialize($this->fileContent[2]);
	}

	/**
	 * Extract needed directories from given extensionDataFilesArray
	 *
	 * @return array
	 */
	protected function extractDirectoriesFromExtensionData() {
		$directories = array();
		foreach ($this->files['FILES'] as $filePath => $file) {
			preg_match('/(.*)\\//', $filePath, $matches);
			if (isset($matches[0]) === TRUE) {
				$directories[] = $matches[0];
			}
		}
		return $directories;
	}

	/**
	 * Loops over an array of directories and creates them in the given root path
	 * It also creates nested directory structures
	 *
	 * @return void
	 */
	protected function createDirectoriesForExtensionFiles() {
		foreach ($this->directories as $directory) {
			if (is_dir($this->extensionDir . $directory) === FALSE) {
				mkdir($this->extensionDir . $directory, 0777, TRUE);
			}
		}
	}

	protected function writeExtensionFiles() {
		foreach ($this->files['FILES'] as $file) {
			file_put_contents($this->extensionDir . $file['name'], $file['content']);
		}
	}

	/**
	 * @param string $t3xFileName
	 */
	public function setT3xFileName($t3xFileName) {
		$this->t3xFileName = $t3xFileName;
	}

	/**
	 * @return string
	 */
	public function getT3xFileName() {
		return $this->t3xFileName;
	}

	/**
	 * @param string $extensionDir
	 */
	public function setExtensionDir($extensionDir) {
		if (strpos($extensionDir, -1) !== '/') {
			$extensionDir .= '/';
		}
		$this->extensionDir = $extensionDir;
	}

	/**
	 * @return string
	 */
	public function getExtensionDir() {
		return $this->extensionDir;
	}
}
?>