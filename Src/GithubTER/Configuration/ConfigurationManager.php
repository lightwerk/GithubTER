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
 * The ConfigurationManager retrieves the merged configuration of Configuration/Settings.Default.yml
 * and Configuration/Settings.yml.
 *
 * @author Philipp Bergsmann <p.bergsmann@opendo.at>
 * @package GithubTer
 */
namespace GithubTER\Configuration;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use GithubTER\Configuration\Settings;

class ConfigurationManager {
	const CONFIGURATION_DIRECTORY = 'Configuration';
	const SETTINGS_DEFAULT_FILENAME = 'Settings.Default.yml';
	const SETTINGS_LOCAL_FILENAME = 'Settings.yml';

	/**
	 * @var array
	 */
	protected $processedConfiguration;

	/**
	 * Locates the configuration-files and builds the configuration-tree
	 *
	 * @return ConfigurationManager
	 */
	public function __construct() {
		$configDirectories = array(getcwd() . '/' . self::CONFIGURATION_DIRECTORY);

		$locator = new FileLocator($configDirectories);

		$configuration = array();
		$this->addConfigurationFile($configuration, $locator, self::SETTINGS_DEFAULT_FILENAME);
		$this->addConfigurationFile($configuration, $locator, self::SETTINGS_LOCAL_FILENAME);

		$processor = new Processor();
		$settings = new Settings();

		$this->processedConfiguration = $processor->processConfiguration(
			$settings,
			$configuration
		);
	}

	/**
	 * Retrieves settings. E.g. ConfigurationManager->get('Services.Beanstalkd.Server');
	 *
	 * @param string $configurationPath
	 * @return array
	 */
	public function get($configurationPath) {
		$configuration = $this->processedConfiguration;
		$configurationPath = explode('.', $configurationPath);

		foreach ($configurationPath as $configurationKey) {
			$configuration = $configuration[$configurationKey];
		}

		return $configuration;
	}

	/**
	 * Add a configuration file
	 *
	 * @param $configuration
	 * @param $locator
	 * @param $file
	 * @return void
	 */
	protected function addConfigurationFile(&$configuration, $locator, $file ) {
		$loader = new Loader\YamlLoader($locator);
		try {
			$configuration[] = $loader->load($locator->locate($file));
		} catch (\InvalidArgumentException $e) {

		}
	}

}
?>