<?php

namespace OtapiClient;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

/*** */
class OtApi
{
	/*** */
	private const OTAPI_URL = 'https://otapi.net/service-json/';
	/*** @var string */
	private static string $key;
	/*** @var string|null */
	private static ?string $secret = NULL;
	/*** @var string */
	private static string $lang;
	/*** @var Client|null */
	private static ?Client $client = NULL;

	/**
	 * @param string $method
	 * @param array  $params
	 * @return string|null
	 * @throws OtException
	 */
	public static function request(string $method, array $params = [], ?array $xmlParams = NULL): ?string
	{
		$requestUrl = self::prepareRequest($method, $params, $xmlParams);
		try {
			$response = self::$client->get($requestUrl);
		} catch (GuzzleException $e) {
			throw new OtException($e->getMessage());
		}
		try {
			return (string) $response->getBody();
		} catch (JsonException $e) {
			return NULL;
		}
	}

	/**
	 * @param string $method
	 * @param array  $params
	 * @return array|null
	 */
	private static function prepareRequest(string $method, array $params = [], ?array $xmlParams = NULL): string
	{
		self::createClient();
		if ($xmlParams !== NULL) {
			$params['xmlParameters'] = self::createXmlParameters($xmlParams);
		}
		$params['instanceKey'] = self::getKey();
		$params['language']    = self::getLang();
		foreach ($params as $k => $v) {
			if (is_bool($v)) {
				$params[$k] = $v ? 'true' : 'false';
			}
		}
		$time                = Carbon::now('UTC');
		$params['timestamp'] = $time->format('YmdHis');
		$params['signature'] = self::sign($method, $params);
		return self::OTAPI_URL . $method . '?' . http_build_query($params);
	}

	/**
	 * @param string $method
	 * @param array  $params
	 * @return string
	 */
	private static function sign(string $method, array $params): string
	{
		ksort($params);
		$paramString = $method . implode('', $params) . self::getSecret();
		return hash('sha256', $paramString);
	}

	/**
	 * @param array $parameters
	 * @return string
	 */
	public static function createXmlParameters(array $parameters): string
	{
		$xmlData   = [];
		$xmlData[] = '<SearchItemsParameters>';
		if (isset($parameters['providerId'])) {
			$xmlData[] = '<Provider>' . $parameters['providerId'] . '</Provider>';
		}
		$xmlData[] = '<CategoryId>' . $parameters['categoryId'] . '</CategoryId>';
		if (isset($parameters['minPrice'])) {
			$xmlData[] = '<MinPrice>' . $parameters['minPrice'] . '</MinPrice>';
		}
		if (isset($parameters['maxPrice'])) {
			$xmlData[] = '<MaxPrice>' . $parameters['maxPrice'] . '</MaxPrice>';
		}
		if (isset($parameters['minVolume'])) {
			$xmlData[] = '<MinVolume>' . $parameters['minVolume'] . '</MinVolume>';
		}
		if (isset($parameters['order'])) {
			$xmlData[] = '<OrderBy>' . $parameters['order'] . '</OrderBy>';
		} else {
			$xmlData[] = '<OrderBy>Default</OrderBy>';
		}
		$xmlData[] = '<Features><Feature Name="IsComplete">true</Feature></Features>';
		$xmlData[] = '</SearchItemsParameters>';
		return implode('', $xmlData);
	}

	/*** */
	private static function createClient(): void
	{
		if (self::$client === NULL) {
			self::$client = new Client([
				'headers' => ['Accept' => 'application/json'],
				'verify'  => FALSE,
			]);
		}
	}

	/**
	 * @return string
	 */
	private static function getKey(): string
	{
		return self::$key;
	}

	/**
	 * @param string $key
	 * @throws OtException
	 */
	public static function setKey(string $key): void
	{
		if ($key === '') {
			throw new OtException('Wrong OTAPI key');
		}
		self::$key = $key;
	}

	/**
	 * @return string
	 */
	private static function getSecret(): string
	{
		return self::$secret;
	}

	/**
	 * @param string $secret
	 * @throws OtException
	 */
	public static function setSecret(string $secret): void
	{
		if ($secret === '') {
			throw new OtException('Wrong OTAPI secret');
		}
		self::$secret = $secret;
	}

	/**
	 * @return string
	 */
	private static function getLang(): string
	{
		return self::$lang;
	}

	/**
	 * @param string $lang
	 * @throws OtException
	 */
	public static function setLang(string $lang): void
	{
		if (strlen($lang) !== 2) {
			throw new OtException('Wrong OTAPI language');
		}
		self::$lang = $lang;
	}

}
