<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Georg Ringer <typo3@ringerge.org>
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
 * Copy of some TYPO3 utility classes to be able to reuse code
 *
 * @author Georg Ringer <typo3@ringerge.org>
 */
namespace GithubTER\Service;

class Utility {
	/**
	 * This function converts version range strings (like '4.2.0-4.4.99') to an array
	 * (like array('4.2.0', '4.4.99'). It also forces each version part to be between
	 * 0 and 999
	 *
	 * @param string $versionsString
	 * @return array
	 */
	static public function convertVersionsStringToVersionNumbers($versionsString) {
		$versions = self::trimExplode('-', $versionsString);
		$versionsCount = count($versions);
		for ($i = 0; $i < $versionsCount; $i++) {
			$cleanedVersion = self::trimExplode('.', $versions[$i]);
			$cleanedVersionCount = count($cleanedVersion);
			for ($j = 0; $j < $cleanedVersionCount; $j++) {
				$cleanedVersion[$j] = static::forceIntegerInRange($cleanedVersion[$j], 0, 999);
			}
			$cleanedVersionString = implode('.', $cleanedVersion);
			if (static::convertVersionNumberToInteger($cleanedVersionString) === 0) {
				$cleanedVersionString = '';
			}
			$versions[$i] = $cleanedVersionString;
		}
		return $versions;
	}


	/**
	 * Explodes a string and trims all values for whitespace in the ends.
	 * If $onlyNonEmptyValues is set, then all blank ('') values are removed.
	 *
	 * @param string $delim Delimiter string to explode with
	 * @param string $string The string to explode
	 * @param boolean $removeEmptyValues If set, all empty values will be removed in output
	 * @param integer $limit If positive, the result will contain a maximum of
	 * @return array Exploded values
	 */
	static public function trimExplode($delim, $string, $removeEmptyValues = FALSE, $limit = 0) {
		$explodedValues = explode($delim, $string);
		$result = array_map('trim', $explodedValues);
		if ($removeEmptyValues) {
			$temp = array();
			foreach ($result as $value) {
				if ($value !== '') {
					$temp[] = $value;
				}
			}
			$result = $temp;
		}
		if ($limit != 0) {
			if ($limit < 0) {
				$result = array_slice($result, 0, $limit);
			} elseif (count($result) > $limit) {
				$lastElements = array_slice($result, $limit - 1);
				$result = array_slice($result, 0, $limit - 1);
				$result[] = implode($delim, $lastElements);
			}
		}
		return $result;
	}

	/**
	 * Forces the integer $theInt into the boundaries of $min and $max. If the $theInt is FALSE then the $defaultValue is applied.
	 *
	 * @param integer $theInt Input value
	 * @param integer $min Lower limit
	 * @param integer $max Higher limit
	 * @param integer $defaultValue Default value if input is FALSE.
	 * @return integer The input value forced into the boundaries of $min and $max
	 */
	static public function forceIntegerInRange($theInt, $min, $max = 2000000000, $defaultValue = 0) {
		// Returns $theInt as an integer in the integerspace from $min to $max
		$theInt = intval($theInt);
		// If the input value is zero after being converted to integer,
		// defaultValue may set another default value for it.
		if ($defaultValue && !$theInt) {
			$theInt = $defaultValue;
		}
		if ($theInt < $min) {
			$theInt = $min;
		}
		if ($theInt > $max) {
			$theInt = $max;
		}
		return $theInt;
	}

	/**
	 * Returns an integer from a three part version number, eg '4.12.3' -> 4012003
	 *
	 * @param string $versionNumber Version number on format x.x.x
	 * @return integer Integer version of version number (where each part can count to 999)
	 */
	static public function convertVersionNumberToInteger($versionNumber) {
		$versionParts = explode('.', $versionNumber);
		return intval(((int)$versionParts[0] . str_pad((int)$versionParts[1], 3, '0', STR_PAD_LEFT)) . str_pad((int)$versionParts[2], 3, '0', STR_PAD_LEFT));
	}
}

?>