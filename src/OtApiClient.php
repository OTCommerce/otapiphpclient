<?php

namespace OtApiClient;

use OtApiClient\ValuesObject\OtParameters;
use OtApiClient\ValuesObject\OtXmlParameters;

/**
 * Class OtApiClient
 * @package OtapiClient
 */
class OtApiClient
{

	/**
	 * @param string $key
	 * @param string $secret
	 * @param string $lang
	 * @throws OtException
	 */
	public function __construct(string $key, string $secret, string $lang)
	{
		OtApi::setKey($key);
		OtApi::setSecret($secret);
		$this->setLang($lang);
	}

	/**
	 * @param string $lang
	 * @return void
	 * @throws OtException
	 */
	public function setLang(string $lang): void
	{
		Otapi::setLang($lang);
	}

	/**
	 * @param string $itemId
	 * @return string|null
	 * @throws OtException
	 */
	public function getItemFullInfo(string $itemId): ?string
	{
		$params = new OtParameters();
		$params->setItemId($itemId);
		return Otapi::request('GetItemFullInfo', $params);
	}

	/**
	 * @param OtParameters    $parameters
	 * @param OtXmlParameters $xmlParameters
	 * @return string|null
	 * @throws OtException
	 */
	public function batchSearchItemsFrame(OtParameters $parameters, OtXmlParameters $xmlParameters): ?string
	{
		return Otapi::request('BatchSearchItemsFrame', $parameters, $xmlParameters);
	}

}
