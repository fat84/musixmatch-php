<?php

namespace Iainmullan\Musixmatch;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Cache\CacheSubscriber;
use GuzzleHttp\Subscriber\Cache\CacheStorage;
use GuzzleHttp\Subscriber\Cache\DefaultCacheStorage;

use Doctrine\Common\Cache\FilesystemCache;

class Musixmatch {

	private $client;
	public $response;

	function __construct($apiKey, $cacheDir = false, $cacheLength = 3600) {

		$this->client = new Client([
		    'base_url' => ['http://api.musixmatch.com/ws/{version}/', ['version' => '1.1']],
		    'defaults' => [
		        'query'   => ['apikey' => $apiKey, 'format' => 'json']
		    ]
		]);

		if ($cacheDir !== false) {

		    $storage = new CacheStorage(
		        new FilesystemCache($cacheDir), '.musix', $cacheLength
			);

			CacheSubscriber::attach($this->client, array(
				'storage' => $storage,
			));

		}

	}

	public function method($methodName, $params = array()) {

		$request = $this->client->createRequest('GET', $methodName, [
			'query' => $params
		]);

		$this->response = $this->client->send($request);

		$data = $this->response->getBody();

		if (!$data) {
			return FALSE;
		}

		$data = json_decode($data, true); 

		return $data['message']['body'];
	}

}
