<?php

namespace Iainmullan\Musixmatch;

use GuzzleHttp\Client;

class Musixmatch {

	private $client;

	function __construct($apiKey) {

		$this->client = new Client([
		    'base_url' => ['http://api.musixmatch.com/ws/{version}/', ['version' => '1.1']],
		    'defaults' => [
		        'query'   => ['apikey' => $apiKey, 'format' => 'json']
		    ]
		]);

	}

	public function method($methodName, $params = array()) {
		
		$response = $this->client->get($methodName, [
			'query' => $params
		]);

		$data = $response->getBody();

		if (!$data) {
			return FALSE;
		}

		$data = json_decode($data, true); 

		return $data['message']['body'];
	}

}
