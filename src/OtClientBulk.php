<?php

namespace OtapiClient;

use JsonMachine\JsonMachine;
use JsonException;
use OtapiClient\ValuesObject\OtParameters;
use OtapiClient\ValuesObject\OtXmlParameters;

/**
 * Class OtClientBulk
 * @package OtapiClient
 */
class OtClientBulk extends OtClient
{
	/*** @var bool */
	private bool $returnWihoutCheck = FALSE;
	/*** @var bool */
	private bool $returnItems = FALSE;

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
					$data                       = json_encode($decoded, JSON_THROW_ON_ERROR);
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
	 * @param OtParameters    $parameters
	 * @param OtXmlParameters $xmlParameters
	 * @return string|null
	 * @throws OtException
	 */
	public function runBulkSearchItems(OtParameters $parameters, OtXmlParameters $xmlParameters): ?string
	{
		$data = Otapi::request('RunBulkSearchItems', $parameters, $xmlParameters);
		if ($data) {
			try {
				$decoded = json_decode($data, TRUE, 512, JSON_THROW_ON_ERROR);
				if (isset($decoded['ErrorCode']) && $decoded['ErrorCode'] === 'Ok') {
					$decoded['Result']['activityId'] = $decoded['Result']['Id']['Value'];
					$data                            = json_encode($decoded, JSON_THROW_ON_ERROR);
				}
			} catch (JsonException $e) {
				throw new OtException('answer decoded error');
			}
		}
		return $data;
	}

	/**
	 * @param OtParameters    $parameters
	 * @param OtXmlParameters $xmlParameters
	 * @param bool            $asStream
	 * @return string|resource|null
	 * @throws OtException
	 */
	public function getBulkSearch(OtParameters $parameters, OtXmlParameters $xmlParameters, bool $asStream = FALSE)
	{
		$data = $this->runBulkSearchItems($parameters, $xmlParameters);
		if ($data) {
			try {
				$decoded = json_decode($data, TRUE, 512, JSON_THROW_ON_ERROR);
				if (isset($decoded['ErrorCode']) && $decoded['ErrorCode'] === 'Ok') {
					$activityId = $decoded['Result']['Id']['Value'];
					$bulkDone   = FALSE;
					while ($bulkDone === FALSE) {
						$resultData = $this->getBulkSearchItemsResult($activityId, $asStream);
						if ($resultData === NULL) {
							throw new OtException('bulk search failed');
						}
						if ($this->returnItems === TRUE) {
							return $resultData;
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
	 * @param int             $frameSize
	 * @param OtXmlParameters $xmlParameters
	 * @param bool            $asSteam
	 * @return JsonMachine
	 * @throws OtException
	 */
	public function getBulkSearchDecoded(int $frameSize, OtXmlParameters $xmlParameters, bool $asSteam = FALSE): JsonMachine
	{
		$parameters = new OtParameters();
		$parameters->setFrameSize($frameSize);
		$parameters->setFramePosition(0);
		$this->returnWihoutCheck = TRUE;
		$this->returnItems       = FALSE;
		if ($asSteam) {
			$stream = $this->getBulkSearch($parameters, $xmlParameters, TRUE);
			$items  = JsonMachine::fromStream($stream, '/Result/Items');;
		} else {
			$items = JsonMachine::fromString($this->getBulkSearch($parameters, $xmlParameters), '/Result/Items');;
		}
		$this->returnWihoutCheck = FALSE;
		return $items;
	}

	/**
	 * @param string $activityId
	 * @param bool   $asStream
	 * @return string|resource|null
	 * @throws OtException
	 */
	public function getBulkSearchItemsResult(string $activityId, bool $asStream = FALSE)
	{
		$params = new OtParameters();
		$params->setActivityId($activityId);
		$params->setGetResult(FALSE);
		$data = Otapi::request('GetBulkSearchItemsResult', $params);
		if ($data !== NULL && str_contains($data, 'Result":{"IsFinished":true')) {
			$params->setGetResult(TRUE);
			$this->returnItems = TRUE;
			return Otapi::request('GetBulkSearchItemsResult', $params, NULL, $asStream);
		}
		return $data;
	}

	/**
	 * @param array $itemIds
	 * @return string|null
	 * @throws OtException
	 */
	public function runBulkItems(array $itemIds): ?string
	{
		$params = new OtParameters();
		$params->setIds($itemIds);
		$data = Otapi::request('RunBulkItems', $params);
		if ($data) {
			try {
				$decoded = json_decode($data, TRUE, 512, JSON_THROW_ON_ERROR);
				if (isset($decoded['ErrorCode']) && $decoded['ErrorCode'] === 'Ok') {
					$decoded['Result']['activityId'] = $decoded['Result']['Id']['Value'];
					$data                            = json_encode($decoded, JSON_THROW_ON_ERROR);
				}
			} catch (JsonException $e) {
				throw new OtException('answer decoded error');
			}
		}
		return $data;
	}

	/**
	 * @param string $activityId
	 * @param bool   $asSteam
	 * @return string|resource|null
	 * @throws OtException
	 */
	public function getBulkItemsResult(string $activityId, bool $asSteam = FALSE)
	{
		$params = new OtParameters();
		$params->setActivityId($activityId);
		$params->setGetResult(FALSE);
		$data = Otapi::request('GetBulkItemsResult', $params);
		if ($data !== NULL && str_contains($data, 'Result":{"IsFinished":true')) {
			$params->setGetResult(TRUE);
			$this->returnItems = TRUE;
			return Otapi::request('GetBulkItemsResult', $params, NULL, $asSteam);
		}
		return $data;
	}

	/**
	 * @param array $itemIds
	 * @param bool  $asSteam
	 * @return string|resource||null
	 * @throws OtException
	 */
	public function getBulkItemsAtOnce(array $itemIds, bool $asSteam = FALSE)
	{
		$data = $this->runBulkItems($itemIds);
		if ($data) {
			try {
				$decoded = json_decode($data, TRUE, 512, JSON_THROW_ON_ERROR);
				if (isset($decoded['ErrorCode']) && $decoded['ErrorCode'] === 'Ok') {
					$activityId = $decoded['Result']['Id']['Value'];
					$bulkDone   = FALSE;
					while ($bulkDone === FALSE) {
						$resultData = $this->getBulkItemsResult($activityId, $asSteam);
						if ($this->returnItems === TRUE) {
							return $resultData;
						}
						if ($resultData === NULL) {
							throw new OtException('bulk items failed');
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
	 * @param array $itemIds
	 * @param bool  $asSteam
	 * @return JsonMachine
	 * @throws OtException
	 */
	public function getBulkItemsDecoded(array $itemIds, bool $asSteam = FALSE): JsonMachine
	{
		$this->returnWihoutCheck = TRUE;
		$this->returnItems       = FALSE;
		if ($asSteam) {
			$stream = $this->getBulkItemsAtOnce($itemIds, TRUE);
			$items  = JsonMachine::fromStream($stream, '/Result/Items');;
		} else {
			$items = JsonMachine::fromString($this->getBulkItemsAtOnce($itemIds), '/Result/Items');
		}
		$this->returnWihoutCheck = FALSE;
		return $items;
	}

}
