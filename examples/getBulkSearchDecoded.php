<?php

use App\Conf;
use OtApiClient\OtClientBulk;
use OtApiClient\OtException;
use OtApiClient\ValuesObject\OtXmlItemParameters;

include_once __DIR__ . "/ini.php";
try {
	$client    = new OtClientBulk(Conf::getConfigValue('OT_Key', ''), Conf::getConfigValue('OT_Secret', ''), Conf::getConfigValue('OT_Lang', ''));
	$xmlParameters = new OtXmlItemParameters();
	$xmlParameters->setCategoryId('otc-46');
	$xmlParameters->setMinVolume(30);
	$itemsData = $client->getBulkSearchDecoded(300, $xmlParameters, FALSE);
	echo '<pre>';
	$i = 0;
	foreach ($itemsData as $item) {
		$i++;
		print $i.' - '.$item['Id'] . PHP_EOL;
	}

} catch (OtException $e) {
	print $e->getMessage();
}