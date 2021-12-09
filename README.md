# OtapiPhpClient

## Create Client
````
$client = new OtClient($key, $secret, $lang);
````
 - key (Access Key)
 - secret (Secret for access key)
 - language (2 symbol lang identifier)

## getBriefCatalog
Get full OTAPI catalog for supported providers
````
$client->getBriefCatalog('otc-46');
````
 - 'otc-46' is optional parameter if you what to get only specified tree.
 
## runBulkSearchItems
Start bulk search
````
$client->getBulkSearchDecoded($parameters, $xmlParameters->getData());
````

**$parameters**
 - framePosition (offset for search, default 0)
 - frameSize (items amount for search, default 1000, max value 10000)

**$xmlParameters**

Filter for search  
````
$xmlParameters = new OtXmlParameters();
$xmlParameters->setCategoryId('otc-46');
````
Otapi category Id for search
$xmlParameters->setMinVolume(30);
Min amount of sales

Method return answer with activityId for getting result

## getBulkSearchItemsResult
````
$client->getBulkSearchItemsResult($activityId);
````
$activityId form runBulkSearchItems request

return json string with all data
If Result IsFinished = FALSE repeat request. Answer is not ready.

## getBulkSearch
````
$client->getBulkSearch($parameters, $xmlParameters->getData());
````
This method start bulk search and wait for answer.
May take a long time (30 or more seconds).

## getBulkSearchDecoded
````
$client->getBulkSearchDecoded($parameters, $xmlParameters->getData());
````
This method is similar to getBulkSearch.
As answer you will get JsonMachine object.
Than you can use it in loop to parse all items.

````
$items  = $client->getBulkSearchDecoded($parameters, $xmlParameters->getData());
foreach ($items as $item){
    echo $item['Id'];
}
````

## getItemFullInfo
````
$client->getItemFullInfo('627419924025');
````
627419924025 is itemId parameter.

return full item data for specified ItemId.