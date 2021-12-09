<?php

namespace OtapiClient;

use JsonMachine\JsonMachine;
use JsonException;

/*** */
class OtClient
{
	/*** @var bool */
	private bool $returnWihoutCheck = FALSE;
	/*** @var bool */
	private bool $returnItems = FALSE;

	/**
	 * @param string $key
	 * @param string $secret
	 * @param string $lang
	 * @throws OtException
	 */
	public function __construct(string $key, string $secret, string $lang)
	{
		Otapi::setKey($key);
		Otapi::setSecret($secret);
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
		$data = OtApi::request('GetBriefCatalog');
		if ($data && $categoryId) {
			try {
				$decoded = json_decode($data, TRUE, 512, JSON_THROW_ON_ERROR);
				if (isset($decoded['Result']['Roots'])) {
					$category                   = $this->getBriefCatalogId($decoded['Result']['Roots'], $categoryId);
					$decoded['Result']['Roots'] = [0 => $category];
					$data                       = json_encode($decoded);
				}
			} catch (JsonException $e) {
				throw new OtException('answer decoded error');
			}
		}
		return $data;
	}

	/**
	 * @param array  $childs
	 * @param string $categoryId
	 * @return array|string|null
	 * @throws OtException
	 */
	private function getBriefCatalogId(array $childs, string $categoryId): ?array
	{
		foreach ($childs as $child) {
			if ($child['Id'] === $categoryId) {
				return $child;
			}
			if (isset($child['Children'])) {
				$findId = $this->getBriefCatalogId($child['Children'], $categoryId);
				if ($findId !== NULL) {
					return $findId;
				}
			}
		}
		return NULL;
	}

	/**
	 * @param array $parameters
	 * @param array $xmlParameters
	 * @return string|null
	 * @throws OtException
	 */
	public function runBulkSearchItems(array $parameters, array $xmlParameters): ?string
	{
		$data = Otapi::request('RunBulkSearchItems', $parameters, $xmlParameters);
		if ($data) {
			try {
				$decoded = json_decode($data, TRUE, 512, JSON_THROW_ON_ERROR);
				if (isset($decoded['ErrorCode']) && $decoded['ErrorCode'] === 'Ok') {
					$decoded['Result']['activityId'] = $decoded['Result']['Id']['Value'];
					$data                            = json_encode($decoded);
				}
			} catch (JsonException $e) {
				throw new OtException('answer decoded error');
			}
		}
		return $data;
	}

	/**
	 * @param array $parameters
	 * @param array $xmlParameters
	 * @return string|null
	 * @throws OtException
	 */
	public function getBulkSearch(array $parameters, array $xmlParameters): ?string
	{
		$data = $this->runBulkSearchItems($parameters, $xmlParameters);
		if ($data) {
			try {
				$decoded = json_decode($data, TRUE, 512, JSON_THROW_ON_ERROR);
				if (isset($decoded['ErrorCode']) && $decoded['ErrorCode'] === 'Ok') {
					$activityId = $decoded['Result']['Id']['Value'];
					$bulkDone   = FALSE;
					while ($bulkDone === FALSE) {
						if ($this->returnItems === TRUE) {
							return $this->getBulkSearchItemsResult($activityId);
						}
						$resultData = $this->getBulkSearchItemsResult($activityId);
						if ($resultData === NULL) {
							throw new OtException('bulk serach failed');
						}
						if ( ! str_contains($resultData, 'Result":{"IsFinished":false')) {
							return $resultData;
						}
					}
				}
			} catch (JsonException $e) {
				throw new OtException('answer decoded error');
			}
		}
		return NULL;
	}

	/**
	 * @param array $parameters
	 * @param array $xmlParameters
	 * @return JsonMachine
	 * @throws OtException
	 */
	public function getBulkSearchDecoded(array $parameters, array $xmlParameters): JsonMachine
	{
		$this->returnWihoutCheck = TRUE;
		$this->returnItems       = FALSE;
		$items                   = JsonMachine::fromString($this->getBulkSearch($parameters, $xmlParameters), '/Result/Items');;
		$this->returnWihoutCheck = FALSE;
		return $items;
	}

	/**
	 * @param string $activityId
	 * @return string|null
	 * @throws OtException
	 */
	public function getBulkSearchItemsResult(string $activityId): ?string
	{
		$params = ['activityId' => $activityId, 'getResult' => FALSE];
		$data   = Otapi::request('GetBulkSearchItemsResult', $params);
		if ($data !== NULL && str_contains($data, 'Result":{"IsFinished":true')) {
			$params            = ['activityId' => $activityId, 'getResult' => TRUE];
			$this->returnItems = TRUE;
			return Otapi::request('GetBulkSearchItemsResult', $params);
		}
		return $data;
	}

}
