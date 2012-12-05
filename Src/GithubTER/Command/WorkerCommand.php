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
use GithubTER\Service as Service;
use GithubTER\Domain\Model as Model;

class WorkerCommand extends Console\Command\Command {
	/**
	 * @var \Pheanstalk
	 */
	protected $beanstalk;

	/**
	 * @var Console\Output\OutputInterface
	 */
	protected $output;

	/**
	 * @var Console\Input\InputInterface
	 */
	protected $input;

	/**
	 * @var Service\Download\DownloadInterface
	 */
	protected $downloadService;

	/**
	 * @var Github_Client
	 */
	protected $github;

	/**
	 * Connects to the beanstalk server
	 *
	 * @param \Symfony\Component\Console\Input\InputInterface $input
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 * @return void
	 */
	protected function initialize(Console\Input\InputInterface $input, Console\Output\OutputInterface $output) {
		$this->beanstalk = new \Pheanstalk('192.168.1.254');
		$this->output = $output;
		$this->input = $input;
		$this->downloadService = new Service\Download\Curl();
		$this->github = new \Github_Client();
	}

	protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output) {
		if ($input->getOption('parse') === TRUE) {
			$output->writeln('Starting parser (file: ' . $this->input->getArgument('extensionlist') . ')');
			$this->parse();
		}

		if ($input->getOption('tag')) {
			$this->tag();
		}
	}

	protected function parse() {
		$mapper = new \GithubTER\Mapper\ExtensionMapper();
		$mapper->loadExtensionList($this->input->getArgument('extensionlist'));
		$mapper->run();
		$mappedResult = $mapper->getMappedResult();

		foreach ($mappedResult as $extension) {
			$this->beanstalk->putInTube('extensions', serialize($extension));
		}
	}

	protected function tag() {
		$this->output->writeln(array(
			'Starting the tagger',
			'Waiting for a job'
		));

		/** @var $job \Pheanstalk_Job */
		$job = $this->beanstalk->watch('extensions')->reserve();

		/** @var $extInfo Model\Extension */
		$extInfo = unserialize($job->getData());
		var_dump($extInfo);

		$this->output->writeln('Starting job ' . $job->getId() . ': "' . $extInfo->getKey() . '"');

		$this->output->writeln('Finished job (ID: ' . $job->getId() . ')');
		$this->beanstalk->delete($job);
	}
}
?>