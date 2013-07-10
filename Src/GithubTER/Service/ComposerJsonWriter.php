<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2013 Felix Oertel <fo@lightwerk.com>
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
 * @author Felix Oertel
 */
namespace GithubTER\Service;

class ComposerJsonWriter {

	/**
	 * @var \GithubTER\Domain\Model\Extension
	 */
	protected $extension;

	public function __construct($extension) {
		$this->extension = $extension;
	}

	public function write() {
		$content = json_encode($this->getConfiguration(), JSON_HEX_QUOT | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		file_put_contents($this->getFilePath(), $content);
	}

	/**
	 * Generate the configuration for the composer.json file
	 *
	 * @return array
	 */
	protected function getConfiguration() {
		$authors = array();

		foreach ($this->extension->getAuthors() as $author) {
			$authors[] = array(
				'name' => $author->getName(),
				'email' => $author->getEmail(),
				'role' => 'Developer',
			);
		}

		return array(
			'name' => 'typo3-ter/' . $this->extension->getKey(),
			'description' => $this->extension->getDescription(),
			'type' => 'typo3-cms-extension',
			'homepage' => 'http://typo3.org/extensions/repository/view/' . $this->extension->getKey(),
			'authors' => $authors,
		);
	}

	/**
	 * Get the file path to the readme file
	 *
	 * @return string
	 */
	protected function getFilePath() {
		return getcwd() . '/Temp/Extension/' . $this->extension->getKey() . '/composer.json';
	}
}