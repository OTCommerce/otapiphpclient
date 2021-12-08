<?php

namespace OtapiClient;

use JsonException;

/*** */
class OtClient
{

	/**
	 * @param string $key
	 * @param string $secret
	 * @param string $lang
	 * @throws OtException
	 */
	public function __construct(string $key, string $secret, string $lang)
	{
		Otapi::setKey($key);
		Otapi::setKey($secret);
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
	 * @param string|null $categoryId
	 * @return string|null
	 * @throws OtException
	 */
	public function getBriefCatalog(string $categoryId = NULL): ?string
	{
		$data = Otapi::request('GetBriefCatalog');
		if ($data && $categoryId){
			try {
				$decode = json_decode($data, TRUE, 512, JSON_THROW_ON_ERROR);
			} catch (JsonException $e) {
				throw new OtException('answer decoded error');
			}
		}
		return $data;
	}

}
