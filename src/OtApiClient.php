<?php

namespace OtApiClient;

use http\Params;
use OtApiClient\ValuesObject\OtParameters;
use OtApiClient\ValuesObject\OtXmlItemParameters;
use OtApiClient\ValuesObject\OtXmlSearchParameters;

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
	 * @return string|NULL
	 * @throws OtException
	 */
	public function getProviderInfoList(): ?string
	{
		$params = new OtParameters();
		return Otapi::request('GetProviderInfoList', $params);
	}


	/**
	 * @param string $provider
	 * @return string|NULL
	 * @throws OtException
	 */
	public function getProviderBriefCatalog(string $provider): ?string
	{
		$params = new OtParameters();
		$params->setProviderType($provider);
		return Otapi::request('GetProviderBriefCatalog', $params);
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
	 * @param string $itemId
	 * @return string|NULL
	 * @throws OtException
	 */
	public function getDescription(string $itemId): ?string
	{
		$params = new OtParameters();
		$params->setItemId($itemId);
		return Otapi::request('GetItemDescription', $params);
	}

	/**
	 * @param string $itemReviewId
	 * @return string|NULL
	 * @throws OtException
	 */
	public function getItemReview(string $itemReviewId): ?string
	{
		$params = new OtParameters();
		$params->setItemReviewId($itemReviewId);
		return Otapi::request('GetItemReview', $params);
	}

	/**
	 * @param string       $itemId
	 * @param OtParameters $params
	 * @param bool         $internal
	 * @return string|NULL
	 * @throws OtException
	 */
	public function searchItemReviews(string $itemId, OtParameters $params, bool $internal = FALSE): ?string
	{
		$xmlParameters = new OtXmlSearchParameters();
		$xmlParameters->setItemId($itemId);
		if ($internal){
			$xmlParameters->setSource('Internal');
		}
		else{
			$xmlParameters->setSource('Provider');
		}
		return Otapi::request('SearchItemReviews', $params, $xmlParameters);
	}

	/**
	 * @param OtParameters        $parameters
	 * @param OtXmlItemParameters $xmlParameters
	 * @return string|null
	 * @throws OtException
	 */
	public function batchSearchItemsFrame(OtParameters $parameters, OtXmlItemParameters $xmlParameters): ?string
	{
		return Otapi::request('BatchSearchItemsFrame', $parameters, $xmlParameters);
	}

}
