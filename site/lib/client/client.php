<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2013 - Murat Erkenov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

class LGLClient {
	static function getInstance() {
		static $instance;
		if (is_null($instance)) {
			$params = JComponentHelper::getParams('com_lgl');
			$instance = new LGLClient(
				$params->get('provider'),
				$params->get('apikey'),
				$params->get('listener')
			);
		}
		return $instance;
	}

	private $url;
	private $apikey;
	private $listenerId;


	/**
	 * @param $url
	 * @param $apikey
	 * @param $listenerId
	 */
	function __construct($url, $apikey, $listenerId) {
		$this->url = $url;
		$this->apikey = $apikey;
		$this->listenerId = $listenerId;
	}

	/**
	 * @param string $command
	 * @param array $data
	 * @return mixed
	 * @throws LGLClientException
	 */
	function sendCommand($command, array $data = array()) {
		$url = $this->url . '/' . $this->apikey . '/' . $this->listenerId . '/' . $command;
		$transport = JHttpFactory::getHttp();
		if (sizeof($data)) {
			$response = $transport->post($url, $data);
		} else {
			$response = $transport->get($url);
		}
		$responseArr = json_decode($response->body, true);
		if (!is_array($responseArr) || !isset($responseArr['status'])) {
			throw new LGLClientException('Can not parse provider\'s response: ' . substr($response->body, 0, 128));
		}
		if ('ok' != strtolower($responseArr['status'])) {
			$err = 'Error returned from provider';
			if (isset($responseArr['error'])) {
				if (isset($responseArr['error']['code'])) {
					$err .= ' Code: ' . $responseArr['error']['code'];
				}
				if (isset($responseArr['error']['message'])) {
					$err .= ' Message: ' . $responseArr['error']['message'];
				}
			}
			throw new LGLClientException($err);
		}
		$return = true;
		if (isset($responseArr['data'])) {
			$return = $responseArr['data'];
		}
		return $return;
	}
}

class LGLClientException extends Exception {

}
