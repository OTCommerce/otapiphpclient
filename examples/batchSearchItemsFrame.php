<?php

use App\Conf;
use OtApiClient\OtClient;
use OtApiClient\ValuesObject\OtBlockList;
use OtApiClient\ValuesObject\OtOrderType;
use OtApiClient\ValuesObject\OtParameters;
use OtApiClient\ValuesObject\OtProvider;
use OtApiClient\ValuesObject\OtXmlItemParameters;

include_once __DIR__ . "/ini.php";
try {
	$client  = new OtClient(Conf::getConfigValue('OT_Key', ''), Conf::getConfigValue('OT_Secret', ''), Conf::getConfigValue('OT_Lang', ''));
	$parameters = new OtParameters();
	$parameters->setFramePosition(0);
	$parameters->setFrameSize(50);
	$blockList = new OtBlockList();
	$blockList->setBrand(TRUE);
	$parameters->setBlockList($blockList);
	$xmlParameters = new OtXmlItemParameters();
	//$xmlParameters->setCategoryId('otc-46');
	$xmlParameters->setProvider(OtProvider::Alibaba1688);
	$xmlParameters->setItemTitle('adidas');
	$xmlParameters->setOrder(OtOrderType::TotalVolumeDesc);
	$items = $client->batchSearchItemsFrame($parameters,$xmlParameters);
	$decoded = json_decode($items, TRUE, 512, JSON_THROW_ON_ERROR);
	print '<pre>';
	print_r($decoded);
} catch (Exception $e) {
	print $e->getMessage();
}