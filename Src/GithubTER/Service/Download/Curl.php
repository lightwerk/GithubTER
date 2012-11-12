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
namespace GithubTER\Service\Download;

class Curl implements DownloadInterface {
	protected $curlHandler;

	public function __construct() {
		$this->curlHandler = curl_init();
	}

	public function __destruct() {
		curl_close($this->curlHandler);
	}

	/**
	 * @param $url
	 * @param $destination
	 * @return mixed
	 */
	public function download($url, $destination) {
		curl_setopt_array($this->curlHandler, array(
			CURLOPT_URL => $url,
			CURLOPT_HEADER => FALSE,
			CURLOPT_RETURNTRANSFER => TRUE
		));

		if (FALSE !== $requestData = curl_exec($this->curlHandler)) {
			file_put_contents($destination, $requestData);

			return $destination;
		}

		return FALSE;
	}

	public function urlExists($url) {
		curl_setopt_array($this->curlHandler, array(
			CURLOPT_URL => $url,
			CURLOPT_NOBODY => TRUE,
			CURLOPT_HEADER => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE
		));

		if (FALSE !== $requestData = curl_exec($this->curlHandler)) {
			$requestData = explode("\n", $requestData);
			$statusCode = explode(' ', $requestData[0]);
			if ($statusCode[1] == '200') {
				return TRUE;
			}
		}

		return FALSE;
	}
}
?>