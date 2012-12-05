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
	 * @var \Github\Client
	 */
	protected $github;

	/**
	 * @var Service\T3xExtractor
	 */
	protected $t3xExtractor;

	/**
	 * @var array
	 */
	protected $existingRepositories;

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
		$this->t3xExtractor = new Service\T3xExtractor();
		$this->github = new \Github\Client();
		$this->github->authenticate('4ed2c0f777b22e9d14ddf4dc99b9ff2b5701e290', '', \Github\Client::AUTH_HTTP_TOKEN);
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

		$organizationRepositories = $this->github->api('organization')->repositories('typo3-ter');
		foreach ($organizationRepositories as $organizationRepository) {
			$this->existingRepositories[$organizationRepository['name']] = $organizationRepository['ssh_url'];
		}

		foreach ($mappedResult as $extension) {
			try {
				$this->github->api('git')->tags()->all('typo3-ter', $extension->getKey());
			} catch (\Exception $e) {
				if (array_key_exists($extension->getKey(), $this->existingRepositories) === FALSE) {
					$this->github->api('repository')->create($extension->getKey(), '', 'http://typo3.org/extensions/repository/view/' . $extension->getKey(), TRUE, 'typo3-ter');
				}

				$extension->setRepositoryPath($this->existingRepositories[$extension->getKey()]);

				$this->beanstalk->putInTube('extensions', serialize($extension));
			}
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
		$extension = unserialize($job->getData());
		$this->output->writeln('Starting job ' . $job->getId() . ': "' . $extension->getKey() . '"');

		$extensionDir = $this->getTempPath() . '/Extension/' . $extension->getKey() . '/';

		if (is_dir($extensionDir)) {
			exec('rm -R ' . escapeshellarg($extensionDir));
		}

		if (is_dir($extensionDir) === FALSE) {
			mkdir($extensionDir, 0777, TRUE);
		}
		$this->beanstalk->delete($job);
print_r($job); die();
		exec(
			'cd ' . escapeshellarg($extensionDir)
				. ' && git init'
				. ' && git remote add origin ' . $extension->getRepositoryPath()
		);

		foreach ($extension->getVersions() as $extensionVersion) {
			$t3xPath = $extensionDir . $extension->getKey() . '.t3x';
			file_put_contents($t3xPath, file_get_contents('http://typo3.org/extensions/repository/download/view/1.0.0/t3x/'));
			$this->t3xExtractor->setT3xFileName($t3xPath);
			$this->t3xExtractor->setExtensionDir($extensionDir);
			$this->t3xExtractor->extract();
			unlink($t3xPath);
			exec(
				'cd ' . escapeshellarg($extensionDir)
					. ' && git add -A'
					. ' && git commit -m "Import of Version ' . $extensionVersion->getNumber() . '"'
					. ' && git tag -a ' . $extensionVersion->getNumber()
					. ' && git push --tags origin master'
			);
		}

		$this->output->writeln('Finished job (ID: ' . $job->getId() . ')');
		$this->beanstalk->delete($job);
	}

	protected function getTempPath() {
		return getcwd() . '/Temp';
	}
}
?>