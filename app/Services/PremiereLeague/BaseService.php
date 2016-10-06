<?php 

namespace App\Services\PremiereLeague;

use JonnyW\PhantomJs\Client;
use Htmldom;
class BaseService {

	public $baseUrl = "https://www.premierleague.com";

	public $timeout = 0; // 20 seconds
	public function render($url, $isLazy = false) 
	{
		$url = htmlspecialchars($url);
		$client = Client::getInstance();

		if($isLazy) {
			$client->isLazy();
		}

		$request = $client->getMessageFactory()->createRequest($url, 'GET');

		$request->addHeader('User-Agent', 'Mozilla/5.0 (Macintosh; Intel Mac OS X) AppleWebKit/534.34 (KHTML, like Gecko) PhantomJS/1.9.2 Safari/534.34');

    

	    /** 
	     * @see JonnyW\PhantomJs\Http\Response 
	     **/
	    $response = $client->getMessageFactory()->createResponse();

	    // Send the request
	   

	    $timeout = $this->timeout;
	   	while ($response->getStatus() != 200) {
	   		dump($url . " @" . $timeout);
	   		$timeout += 5000;
	   		$request->setTimeout($timeout);
	   		
	   		$client->send($request, $response);
		    if($response->getStatus() === 200) {
		        $html = new Htmldom($response->getContent());
		    	return $html;
		    }
	   	}

	    return false;
	}	
}