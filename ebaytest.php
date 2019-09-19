<?php
require '../vendor/autoload.php';

/*
$finding = new DTS\eBaySDK\Finding\Services\FindingService([
	'apiVersion' => '1.13.0',
	'globalId' => DTS\eBaySDK\Constants\GlobalIds::GB

]);
*/
use DTS\eBaySDK\Finding\Services\FindingService;
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Finding\Services;
use \DTS\eBaySDK\Finding\Types;
use \DTS\eBaySDK\Finding\Enums;

//Ebay API settings
$appId = 'KirilsKr-PriceAna-PRD-1393f3dea-cf269b6e';
$certId = '0e3acf83-1393-4ca0-9365-6ecea3ef72fe';
$devId = 'PRD-393f3dea2d76-345c-4637-a9a4-fde8';

if (isset($_GET["keywords"])){
	$keywords = $_GET["keywords"];
}
else {
	$keywords = 'Iphone 7 Plus Cracked';
}

//creating  FindingAPI service
$finding = new FindingService([
    'globalId'    => 'EBAY-GB',
    'credentials' => [
        'appId'  => $appId,
        'certId' => $certId,
        'devId'  => $devId
    ]
]);
//echo DTS\eBaySDK\Sdk::VERSION;


$findReq = new Types\FindCompletedItemsRequest();

$findReq->keywords = $keywords;

//category  = mobile phones
$findReq->categoryId = ['9355'];

$findReq->paginationInput = new Types\PaginationInput();
$findReq->paginationInput->entriesPerPage = 200;
$findReq->paginationInput->pageNumber =1;


//$findReq->itemFilter->ListingType = 'Auction';
//$findReq->itemFilter->Condition = 'Used';
$itemFilter = new Types\ItemFilter();
$itemFilter->name = 'ListingType';
$itemFilter->value[] = 'Auction';
$findReq->itemFilter[] = $itemFilter;

$itemFilter = new Types\ItemFilter();
$itemFilter->name = 'Condition';
$itemFilter->value[] = 'Used';
$findReq->itemFilter[] = $itemFilter;

$itemFilter = new Types\ItemFilter();
$itemFilter->name = 'SoldItemsOnly';
$itemFilter->value[] = 'true';
$findReq->itemFilter[] = $itemFilter;


$promise = $finding->findCompletedItemsAsync($findReq);
$promise->then(function ($response){
	if ($response->ack !== 'Success') {
		echo "Timestamp: ".$response->timestamp->format('Y-m-d H:i:s');
	    foreach ($response->errorMessage->error as $error) {
	        printf("Error: %s\n", $error->message);
	    }
	} else {
		echo "Timestamp: ".$response->timestamp->format('Y-m-d H:i:s')."<br/>";
	    foreach ($response->searchResult->item as $item) {
	        echo "ItemId: <b>".$item->itemId."</b> <a href='".$item->viewItemURL."'>".$item->title."</a> <img src='".$item->galleryURL."'/> endtime: ".$item->listingInfo->endTime->format('Y-m-d H:i:s')." Selling status: ".$item->sellingStatus."<br/>";
	    }
	}
})->otherwise(function ($reason){
	echo 'An error occured: '.$reason->getMessage();
});
?>