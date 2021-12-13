<?php

namespace OtapiClient;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\StreamWrapper;
use JsonException;
use OtapiClient\ValuesObject\OtParameters;
use OtapiClient\ValuesObject\OtXmlParameters;

/**
 * Class OtApi
 * @package OtapiClient
 */
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
	 * @param string               $method
	 * @param OtParameters|null    $params
	 * @param OtXmlParameters|null $xmlParams
	 * @param bool                 $returnAsStream
	 * @return resource|string
	 * @throws OtException
	 */
	public static function request(string $method, OtParameters $params = NULL, ?OtXmlParameters $xmlParams = NULL, bool $returnAsStream = FALSE)
	{
		$requestUrl = self::prepareRequest($method, $params, $xmlParams);
		try {
			$response = self::$client->get($requestUrl);
		} catch (GuzzleException $e) {
			throw new OtException($e->getMessage());
		}
		if ($returnAsStream) {
			return StreamWrapper::getResource($response->getBody());
		}
		$answer = (string) $response->getBody();
		// to not decode to prevent memory overuse
		if ( ! str_starts_with($answer, '{"ErrorCode":"Ok"') && str_starts_with($answer, '{"ErrorCode":')) {
			try {
				$decoded = json_decode($answer, TRUE, 512, JSON_THROW_ON_ERROR);
			} catch (JsonException $e) {
				throw new OtException('request decode error');
			}
			throw new OtException($decoded['ErrorCode'] . ': ' . $decoded['ErrorDescription']);
		}
		return $answer;
	}

	/**
	 * @param string               $method
	 * @param OtParameters|null    $parameters
	 * @param OtXmlParameters|null $xmlParams
	 * @return array|null
	 */
	private static function prepareRequest(string $method, ?OtParameters $parameters = NULL, ?OtXmlParameters $xmlParams = NULL): string
	{
		$params = $parameters ? $parameters->getData() : [];
		self::createClient();
		if ($xmlParams !== NULL) {
			$params['xmlParameters'] = $xmlParams->createXmlParameters();
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

	/*** @return string */
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

	/*** @return string */
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

	/*** @return string */
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
