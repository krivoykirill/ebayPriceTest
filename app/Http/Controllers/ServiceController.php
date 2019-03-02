<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \DTS;
use \DTS\eBaySDK\Finding\Services\FindingService;
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Finding\Services;
use \DTS\eBaySDK\Finding\Types;
use \DTS\eBaySDK\Finding\Enums;
use \DTS\eBaySDK\Credentials\CredentialsInterface;
use \DTS\eBaySDK\Sdk;
use \DTS\eBaySDK\Trading;
use \DTS\eBaySDK\FileTransfer;
use \DTS\eBaySDK\OAuth\Services as OAuthServices;
use \DTS\eBaySDK\OAuth\Types as OAuthTypes;



class ServiceController extends Controller
{
    

    public function __construct() {
        $this->config = require __DIR__.'/../../../ebayConfig.php';

        $this->middleware('auth');

        $this->sdkConfig = [
            'apiVersion'=>'903',
            'siteId'=>'3',
            'globalId'=>'EBAY-GB',
            'credentials'=>$this->config["sandbox"]["credentials"]
        ];

    }
    /*
    public function getToken() {
        

        $oauth= new OAuthServices\OAuthService([
            'credentials'=>$this->config["sandbox"]["credentials"],
            'ruName'=>$this->config["sandbox"]["ruName"],
            'sandbox'=>true
        ]);
        
        $response = $oauth->getAppToken();
        if ($response->getStatusCode() !== 200) {

            return null;

        } else {
            
            return $response->access_token;
        }
        


    }
    */

    public function categorySpecificsIntoDB(){
        $sdk = new Sdk([
            'credentials' => $this->config['sandbox']['credentials'],
            'authToken'   => $this->getToken(),
            'siteId'      => '3',
            'sandbox' => true
        ]);


        $trading=$sdk->createTrading();
        
        $request = new Trading\Types\GetCategorySpecificsRequestType();

        $request->CategorySpecificsFileInfo = true;
        
        $response=$trading->getCategorySpecifics($request);


        if (isset($response->Errors)) {
            foreach ($response->Errors as $error) {
                printf(
                    "%s: %s\n%s\n\n",
                    $error->SeverityCode === Trading\Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                    $error->ShortMessage,
                    $error->LongMessage
                );
            }
        }
        printf($response);
/*
        if ($response->ack !== 'Failure') {

            if ($response->hasAttachment()) {
                $attachment = $response->attachment();

                $tempFilename = tempnam(sys_get_temp_dir(), 'category-specifics-').'.zip';
                $fp = fopen($tempFilename, 'wb');
                if (!$fp) {
                    printf("Failed. Cannot open %s to write!\n", $tempFilename);
                } else {
                    fwrite($fp, $attachment['data']);
                    fclose($fp);
                    printf("File downloaded to %s\nUnzip this file to obtain the category item specifics.\n\n", $tempFilename);
                }
            } else {
                print("Unable to locate attachment\n\n");
            }
        }
    }

        */
    

    }
}

?>
