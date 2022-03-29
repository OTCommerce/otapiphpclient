<?php

use App\Conf;
use OtApiClient\OtClient;

include_once __DIR__ . "/ini.php";
try {
	$client  = new OtClient(Conf::getConfigValue('OT_Key', ''), Conf::getConfigValue('OT_Secret', ''), Conf::getConfigValue('OT_Lang', ''));
	$item = $client->getItemFullInfo('575442381508');
	$decoded = json_decode($item, TRUE, 512, JSON_THROW_ON_ERROR);
	print '<pre>';
	print_r($item);
	print_r($decoded);
} catch (Exception $e) {
	print $e->getMessage();
}