<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2012 Philipp Bergsmann <p.bergsmann@opendo.at>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


/**
 * Writes a basic Readme.md for the git project
 *
 * @author    Georg Ringer
 */
namespace GithubTER\Service;

class ReadmeWriter {

	const ENDLINE_IDENTIFIER = '-- never edit the line below (typo3-ter) --';

	/**
	 * @var \GithubTER\Domain\Model\Extension
	 */
	protected $extension;

	/**
	 * @var \GithubTER\Domain\Model\Version
	 */
	protected $version;

	public function __construct($extension, $version) {
		$this->extension = $extension;
		$this->version = $version;
	}

	public function write() {
		if (!is_file($this->getFilePath())) {
			$this->generateNewFile();
		} else {
			$this->updateFile();
		}
	}

	protected function generateNewFile() {
		$content = $this->getContent();
		file_put_contents($this->getFilePath(), $content);
	}

	protected function updateFile() {
		$content = '';
		$previousContent = file_get_contents($this->getFilePath());
		$endLineIdentiferPosition = strpos($previousContent, self::ENDLINE_IDENTIFIER);

		if ($endLineIdentiferPosition === FALSE) {
			$content = $this->getContent() . $previousContent;
		} else {
			$trimmedReadme = substr($previousContent, $endLineIdentiferPosition + strlen(self::ENDLINE_IDENTIFIER));
			$content = $this->getContent() . $trimmedReadme;
		}

		file_put_contents($this->getFilePath(), $content);
	}

	/**
	 * Generate the content for the custom Readme.md file
	 *
	 * @return string
	 */
	protected function getContent() {
		$content = '## TYPO3 Extension \'' . $this->extension->getKey() . '\' ##' . LF;
		$content .= $this->version->getUploadComment() . LF;

		$dataContent = '';
		$dataTable = array(
			array('Version', $this->version->getNumber() . ' ' . $this->version->getState()),
			array('Release date', date($this->version->getUploadDate()), 'd.M Y'),
			array('Author', $this->version->getAuthor()),
		);
		foreach ($dataTable as $data) {
			$dataContent .= '<tr><td>' . $data[0] . '</td><td>' . $data[1] . '</td></tr>';
		}
		if (!empty($dataContent)) {
			$content .= '<table>' . $dataContent . '</table>';
		}

		$content .= self::ENDLINE_IDENTIFIER;
		return $content;
	}

	/**
	 * Get the file path to the readme file
	 *
	 * @return string
	 */
	protected function getFilePath() {
		return getcwd() . '/Temp/Extension/' . $this->extension->getKey() . '/README.md';
	}
}