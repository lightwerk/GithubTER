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
class UtilityTest extends PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function returnsVersionnumberForGivenString() {
		$fixture = new GithubTER\Service\Utility();

		$input = '1.2.3';
		$expectedOutput = array('1.2.3');

		$actualOutput = $fixture->convertVersionsStringToVersionNumbers($input);

		$this->assertEquals($expectedOutput, $actualOutput);
	}

	/**
	 * @test
	 */
	public function trimExplodeReturnsTrimmedArray() {
		$fixture = new GithubTER\Service\Utility();

		$input = '1, 2, 3';
		$expectedOutput = array(1,2,3);

		$actualOutput = $fixture->trimExplode(',', $input);

		$this->assertEquals($expectedOutput, $actualOutput);
	}

	/**
	 * @test
	 */
	public function convertingVersionnumberToIntegerWorks() {
		$fixture = new GithubTER\Service\Utility();

		$input = '4.12.3';
		$expectedOutput = 4012003;

		$actualOutput = $fixture->convertVersionNumberToInteger($input);

		$this->assertEquals($expectedOutput, $actualOutput);
	}

	/**
	 * @test
	 */
	public function forcingIntegerInRangeWorks() {
		$fixture = new GithubTER\Service\Utility();

		$input = '200';
		$expectedOutput = 100;

		$actualOutput = $fixture->forceIntegerInRange($input, $expectedOutput, $expectedOutput);

		$this->assertEquals($expectedOutput, $actualOutput);
	}
}
?>