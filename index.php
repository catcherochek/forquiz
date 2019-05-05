<?php 
define('CSS_PATH', 'css/');
require  'src/RespondService.php';


/** @var RespondService $rsc creating service, injecting interface to it*/
$rsc = new ApiService(new CacheStore());
if (!isset($_GET['stage'])){
    echo $rsc->Submit();
}
else if ($_GET['stage']=='result'){
    echo $rsc->Respond();
}

		
