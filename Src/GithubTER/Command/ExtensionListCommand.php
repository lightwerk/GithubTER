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
namespace GithubTER\Command;

use Symfony\Component\Console as Console;

class ExtensionListCommand extends Console\Command\Command {
	/**
	 * @var Console\Output\OutputInterface
	 */
	protected $output;

	protected function initialize(Console\Input\InputInterface $input, Console\Output\OutputInterface $output) {
		$this->output = $output;
	}
	/**
	 * Downloads the extensions.xml
	 *
	 * @param \Symfony\Component\Console\Input\InputInterface $input
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 * @return int|null|void
	 */
	protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output) {
		if ($input->getOption('update') === TRUE) {
			$this->download();
			$this->uncompress();
		}

		if ($input->getOption('info') === TRUE) {
			$this->info();
		}
	}

	/**
	 * Downloads the extension XML from the configured mirror
	 *
	 * @return void
	 */
	protected function download() {
		$this->output->writeln(array(
			'Starting download',
			'from: "' . $this->getExtensionListUrl() . '"',
			'to: "' . $this->getTempPath() . '"'
		));

		if (is_dir($this->getTempPath()) === FALSE) {
			mkdir($this->getTempPath());
		}

		file_put_contents($this->getTempPath() . '/extensions.xml.gz', file_get_contents($this->getExtensionListUrl()));

		$this->output->writeln(
			'Finished download (' . round(
				(filesize($this->getTempPath() . '/extensions.xml.gz') / 1024 / 1024),
				2)
			. 'MB)'
		);
	}

	/**
	 * uncompresses the gzip-file
	 *
	 * @return void
	 */
	protected function uncompress() {
		$srcName = $this->getTempPath() . '/extensions.xml.gz';
		$dstName = $this->getTempPath() . '/extensions.xml';

		$this->output->writeln('Starting to uncompress');
		$sfp = gzopen($srcName, "rb");
		$fp = fopen($dstName, "w");

		while ($string = gzread($sfp, 4096)) {
			fwrite($fp, $string, strlen($string));
		}
		gzclose($sfp);
		fclose($fp);

		$this->output->writeln('Uncompressing complete (' . round(filesize($this->getTempPath() . '/extensions.xml') / 1024 / 1024, 2) . 'MB)');
	}

	/**
	 * Writes some infos
	 *
	 * @return void
	 */
	protected function info() {
		$this->output->writeln(array(
			'Last update: ' . date('r', filemtime($this->getTempPath() . '/extensions.xml')),
			'Filesize: ' . round(filesize($this->getTempPath() . '/extensions.xml') / 1024 / 1024) . 'MB'
		));
	}

	/**
	 * Returns the url of the extlist-XML
	 *
	 * @return string
	 */
	protected function getExtensionListUrl() {
		return 'http://typo3.org/fileadmin/ter/extensions.xml.gz';
	}

	protected function getTempPath() {
		return getcwd() . '/Temp';
	}
}
?>