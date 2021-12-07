<?php

namespace OtapiClient;

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
	 * @return string|null
	 * @throws OtException
	 */
	public function getBriefCatalog(): ?string
	{
		return Otapi::request('GetBriefCatalog');
	}

}
