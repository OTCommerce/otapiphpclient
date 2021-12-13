<?php

use App\Conf;
use OtapiClient\OtClientBulk;

include_once __DIR__ . "/ini.php";
try {
	$client  = new OtClientBulk(Conf::getConfigValue('OT_Key', ''), Conf::getConfigValue('OT_Secret', ''), Conf::getConfigValue('OT_Lang', ''));
	// Full catalog
	// $catalog = $client->getBriefCatalog('otc-46');
	$catalog = $client->getBriefCatalog('otc-46');
	$decoded = json_decode($catalog, TRUE, 512, JSON_THROW_ON_ERROR);
	print '<pre>';
	print_r($decoded);
} catch (Exception $e) {
	print $e->getMessage();
}