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
 * The ExtensionList-Command retrieves a new extension-list from a ter-mirror (mirror-URL is configured in Settings.yml
 * with the key "Services.TER.ExtensionListUrl"). The other possibility is to output some information about the local
 * extensions-xml like last-update-date or filesize.
 *
 * @author Philipp Bergsmann <p.bergsmann@opendo.at>
 * @author Georg Ringer
 * @package GithubTER
 */
namespace GithubTER\Command;

use Symfony\Component\Console;

class ExtensionListCommand extends BaseCommand {
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
			'from: "' . $this->configurationManager->get('Services.TER.ExtensionListUrl') . '"',
			'to: "' . $this->configurationManager->get('TempDir') . '"'
		));

		if (is_dir($this->configurationManager->get('TempDir')) === FALSE) {
			$this->output->writeln('Temp-Path "' . $this->configurationManager->get('TempDir') . ' didn_t exist: creating.');
			mkdir($this->configurationManager->get('TempDir'));
		}

		file_put_contents($this->configurationManager->get('TempDir') . '/extensions.xml.gz', file_get_contents($this->configurationManager->get('Services.TER.ExtensionListUrl')));

		$this->output->writeln(
			'Finished download (' . round(
				(filesize($this->configurationManager->get('TempDir') . '/extensions.xml.gz') / 1024 / 1024),
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
		$srcName = $this->configurationManager->get('TempDir') . '/extensions.xml.gz';
		$dstName = $this->configurationManager->get('TempDir') . '/extensions.xml';

		$this->output->writeln('Starting to uncompress');
		$sfp = gzopen($srcName, "rb");
		$fp = fopen($dstName, "w");

		while ($string = gzread($sfp, 4096)) {
			fwrite($fp, $string, strlen($string));
		}
		gzclose($sfp);
		fclose($fp);

		$this->output->writeln('Uncompressing complete (' . round(filesize($dstName) / 1024 / 1024, 2) . 'MB)');
	}

	/**
	 * Writes some infos
	 *
	 * @return void
	 */
	protected function info() {
		if (is_file($this->configurationManager->get('TempDir') . '/extensions.xml')) {
			$this->output->writeln(array(
				'Last update: ' . date('r', filemtime($this->configurationManager->get('TempDir') . '/extensions.xml')),
				'Filesize: ' . round(filesize($this->configurationManager->get('TempDir') . '/extensions.xml') / 1024 / 1024) . 'MB',
				'Extension-List-URL: ' . $this->configurationManager->get('Services.TER.ExtensionListUrl')
			));
		} else {
			$this->output->writeln('No extension list yet downloaded!');
		}
	}
}
?>