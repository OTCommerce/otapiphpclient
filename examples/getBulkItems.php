<?php

use App\Conf;
use JsonMachine\JsonMachine;
use OtapiClient\OtClientBulk;

include_once __DIR__ . "/ini.php";
try {
	$client    = new OtClientBulk(Conf::getConfigValue('OT_Key', ''), Conf::getConfigValue('OT_Secret', ''), Conf::getConfigValue('OT_Lang', ''));
	$items     = [
		'639654286622',
		'626524171724',
		'540755121912',
		'588319323537',
		'601286347796',
		'585878065471',
		'636677152993',
		'627632096613',
		'41757935984',
		'627058476319',
		'564896878138',
		'630216934986',
		'523393298387',
	];
	$runData = $client->runBulkItems($items);
	$runDataDecoded = json_decode($runData, TRUE, 512, JSON_THROW_ON_ERROR);
	$activityId = $runDataDecoded['Result']['activityId'];
	$finished = FALSE;
	$itemsData = NULL;
	while ($finished === FALSE){
		$itemsData = $client->getBulkItemsResult($activityId);
		// not decode, to prevent memory overuse
		if ($itemsData === NULL || !str_contains($itemsData, 'Result":{"IsFinished":false')) {
			$finished = TRUE;
		}
	}
	if ($itemsData !== NULL){
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