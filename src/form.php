<?php 

function LoginForm($params){
	error_reporting(E_ERROR | E_PARSE);
$out = "";	
	if (defined('CSS_PATH')){
		$out .= "<link href=\"".CSS_PATH."form.css\" rel=\"stylesheet\">";
	}
	
$out .= "<h2>{$params['title']}</h2> 

        <form action=\"{$params['action']}\" method = 'GET'> 
                
        <div class=\"container\"> 
            <label><b>Adress</b></label>  
            <input type=\"text\" placeholder=\"Enter Adress\" name=\"addr\" required value = \"{$params['addr_val']}\"> 
        
            <label><b>City</b></label> 
            <input type=\"text\" placeholder=\"Enter City\" name=\"city\" required value = \"{$params['city_val']}\"> 
            
			<label><b>Country Code</b></label> 
            <input type=\"text\" placeholder=\"Enter City\" name=\"cc\" required value = \"{$params['cc_val']}\">  

			<label><b>State code</b></label> 
            <input type=\"text\" placeholder=\"Enter City\" name=\"sc\" required value = \"{$params['sc_val']}\">

			<label><b>zip code</b></label> 
            <input type=\"text\" placeholder=\"Enter City\" name=\"zip\" required value = \"{$params['zip_val']}\"> 

			<label><b>Product Variant ID</b></label> 
            <input type=\"text\" placeholder=\"Enter City\" name=\"prodid\" required value = \"{$params['prodid_val']}\"> 

			<label><b>Quantaty</b></label> 
            <input type=\"text\" placeholder=\"Enter City\" name=\"qnty\" required value = \"{$params['qnty_val']}\">  

			<input  name=\"stage\" type=\"hidden\" value=\"result\">
                
            <button type=\"submit\">Submit</button> 
       
        </div> 
        
        <div class=\"container\" style=\"background-color:#f1f1f1\"> 
            <button type=\"button\" class=\"cancelbtn\">Cancel</button> 
            <span class=\"psw\">Forgot <a href=\"#\">password?</a></span> 
        </div> 
        </form> ";
        //$out .= "<h2>{$params['title']}</h2>";
error_reporting(E_ALL);
return $out;

}
function PrintResults($data){
	$out = "";	
	$out .= "<div><h3>Results</h3>";
	foreach ($data['result'] as $val){
		$out .=	"<div style =\"border:1px solid gray\">
					<p><strong>id:</strong> {$val['id']}</p>
					<p><strong>name:</strong> {$val['name']}</p> 
					<p><strong>rate:</strong>{$val['rate']} {$val['currency']}</p> 
				</div>";}
		
	$out .= "</div>";
	return $out;}
