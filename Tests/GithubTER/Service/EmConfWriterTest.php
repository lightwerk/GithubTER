<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Philipp Bergsmann <p.bergsmann@opendo.at>
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
class EmConfWriterTest extends PHPUnit_Framework_TestCase {
	/**
	 * @test
	 * @dataProvider arrayDependencyData
	 */
	public function dependencyToStringReturnsString($dependency, $type, $expectedResult) {
		$fixture = new GithubTER\Service\EmConfWriter();

		$actualOutput = $fixture->dependencyToString($dependency, $type, $expectedResult);

		$this->assertEquals($expectedResult, $actualOutput);
	}

	/**
	 * @test
	 * @dataProvider stringDependencyData
	 */
	public function stringToDependencyReturnsArray($dependency, $expectedResult) {

	}

	public function stringDependencyData() {
		return array(
				// dataset 1
			array(
				'cms,news',
				array(
					'cms' => '',
					'news' => ''
				)
			),

				// dataset 2
			array(
				'cms,news,',
				array(
					'cms' => '',
					'news' => ''
				)
			),

				// dataset 3
			array(
				'',
				array()
			),

				//dataset 4
			array(
				',cms,extbase',
				array(
					'cms' => '',
					'extbase' => ''
				)
			)
		);
	}

	public function arrayDependencyData() {
		return array(
				// dataset 1
			array(
				array(
					'depends' => array(
						'cms' => '4.5',
						'news' => '1.3'
					)
				),
				'depends',
				'cms,news'
			),

				// dataset 2
			array(
				array(
					'depends' => array()
				),
				'depends',
				''
			),

				// dataset 3
			array(
				array(
					'depends' => array(
						'php' => '5.4',
						'typo3' => '4.7'
					)
				),
				'depends',
				''
			)
		);
	}
}
?>