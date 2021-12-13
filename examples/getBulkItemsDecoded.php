<?php

use App\Conf;
use OtapiClient\OtClientBulk;
use OtapiClient\OtException;

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
	$itemsData = $client->getBulkItemsDecoded($items, FALSE);
	print '<pre>';
	foreach ($itemsData as $item) {
		print_r($item);
	}
} catch (OtException $e) {
	print $e->getMessage();
}