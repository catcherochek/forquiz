<?php 
define('CSS_PATH', 'css/');
require  'src/RespondService.php';


/** @var ApiService $rsc creating service*/
//injecting interface to a service(according to a quiz task)
$rsc = new ApiService(new CacheStore());
if (!isset($_GET['stage'])){
    //1st step creating Submit form
    echo $rsc->Submit();
}
else if ($_GET['stage']=='result'){
    //2nd step Responding
    //according to a quiz:
    //  - if it is first request, using server API to get request from server and store data into the cache
    //  - if it is a next request and it happened before 5 min after 1st request than  retrieving data from filesystem cache
    echo $rsc->Respond();
}

		
