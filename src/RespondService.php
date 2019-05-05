<?php

require './vendor/autoload.php';
require 'parts.php';
require 'CacheStore.php';
use GuzzleHttp\Client;

class ApiService{
    private $cacheService;
    public function __construct(CacheInterface $ci)
    {
        //registering CacheInterface in a Service (according to a task in quiz)
        $this->cacheInterface = $ci;
    }
    public function Submit(){
        //1st part of script, submitting form, adding default values to script
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
        //showing submitting form
        echo SubmitForm($params);
    }
    public function Respond(){
        //2nd part of a script, getting data
        $result = '';
        $out = '';
        //TTL cache validation
        $res = $this->cacheInterface->get('result');



        if ($res){

            //if cache TTL is valid, retrieving data from cache
            //information string
            $out .= "Cache time doesn't expired, retrieving data from cache <br/> script time: ".
                sprintf("%01.5f", (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]))."s";
            $result = $res;
        }
        else{

            // otherwise if TTL is invalid or cache file doesn't exists

            $client = new GuzzleHttp\Client();
            $headers = [
                'cache-control' => 'no-cache',
                'Content-Type' => 'application/json',
                //Api key, encoded with base63 encoding
                'Authorization' => 'Basic NzdxbjlhYXgtcXJybS1pZGtpOmxuaDAtZm0ybmhtcDB5Y2E3'
            ];
            // raw body, with data that was sent from previous step.
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
            //retrieving data from API server
            $response = $client->post('https://api.printful.com/shipping/rates',['headers' => $headers, 'body' => $body]);
            $result =json_decode((string)$response->getBody()->getContents(),true);
            //information string
            $out .= "retrieving data from server, adding  data to cache <br/> script time: ".
                sprintf("%01.5f", (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]))."s";
            //saving data to cache, duration is 5*60 = 300s(according to a task quiz)
            $this->cacheInterface->set('result',$result,300);
        }

        echo $out.PrintResults($result);
    }




}
