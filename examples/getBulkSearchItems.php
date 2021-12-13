<?php

use App\Conf;
use JsonMachine\JsonMachine;
use OtapiClient\OtClientBulk;
use OtapiClient\ValuesObject\OtParameters;
use OtapiClient\ValuesObject\OtXmlParameters;

include_once __DIR__ . "/ini.php";
try {
	$client        = new OtClientBulk(Conf::getConfigValue('OT_Key', ''), Conf::getConfigValue('OT_Secret', ''), Conf::getConfigValue('OT_Lang', ''));
	$xmlParameters = new OtXmlParameters();
	$xmlParameters->setCategoryId('otc-46');
	$xmlParameters->setMinVolume(30);
	$parameters = new OtParameters();
	$parameters->setFrameSize(200);
	$parameters->setFramePosition(0);
	$runData        = $client->runBulkSearchItems($parameters, $xmlParameters);
	$runDataDecoded = json_decode($runData, TRUE, 512, JSON_THROW_ON_ERROR);
	$activityId     = $runDataDecoded['Result']['activityId'];
	$finished       = FALSE;
	$itemsData      = NULL;
	while ($finished === FALSE) {
		$itemsData = $client->getBulkSearchItemsResult($activityId);
		// not decode, to prevent memory overuse
		if ($itemsData === NULL || ! str_contains($itemsData, 'Result":{"IsFinished":false')) {
			$finished = TRUE;
		}
	}
	if ($itemsData !== NULL) {
		// for potential big data we use JsonMachine library
		$items = JsonMachine::fromString($itemsData, '/Result/Items');
		print '<pre>';
		foreach ($items as $item) {
			print_r($item);
		}
	}
} catch (Exception $e) {
	print $e->getMessage();
}