<?php 
define('CSS_PATH', 'css/');
require 'src\CacheStore.php';
require "vendor\autoload.php";
require 'src\form.php';
use GuzzleHttp\Client;


if (isset($_GET['stage'])){
	if ($_GET['stage']=='submit'){
		$params = array (
				'title' => 'Please input params',
				'action' => 'index.php',
				'addr_val'=>'11025 Westlake Dr',
				'city_val'=>'Charlotte',
				'cc_val'=>'US',
				'sc_val'=>'NC',
				'zip_val'=>'28273',
				'prodid_val'=>'7679',
				'qnty_val'=>'2'
		);
		echo LoginForm($params);}
	else if ($_GET['stage']=='result'){
		$result = '';
		$cs = new CacheStore();
		$res = $cs->get('val');
		if ($res){
			echo "Cache time doesn't expired, retrieving data from cache <br/> ".(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"])."s";
			$result = $res;
		}
		else{
			$client = new GuzzleHttp\Client();
			
			$headers = [
					'cache-control' => 'no-cache',
					'Content-Type' => 'application/json',
					'Authorization' => 'Basic NzdxbjlhYXgtcXJybS1pZGtpOmxuaDAtZm0ybmhtcDB5Y2E3'
			];
			
			$body = '{
			    "recipient": {
			        "address1": "'.$_GET['addr'].'",
			        "city": "'.$_GET['city'].'",
			        "country_code": "'.$_GET['cc'].'",
			        "state_code": "'.$_GET['sc'].'",
			        "zip": '.$_GET['zip'].'
			    },
			    "items": [
			        {
			            "quantity": '.$_GET['qnty'].',
			            "variant_id": '.$_GET['prodid'].'
			        }
			    ]
				}';
			
			$response = $client->post('https://api.printful.com/shipping/rates',['headers' => $headers, 'body' => $body]);
			$result =json_decode((string)$response->getBody()->getContents(),true);
			echo "retrieving data from server, putting  data to cache <br/> ".(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"])."s";
			$cs->set('val',$result,300);
		}
		
		
		
		echo PrintResults($result);
		}	
		
	
}else {echo "<a href = 'index.php?stage=submit'>letsgo</a>";
	
	
}





//var_dump($response);


?>