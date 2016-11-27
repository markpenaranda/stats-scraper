<?php 

namespace App\Services\ESPN;

use JonnyW\PhantomJs\Client;
use Htmldom;

class BaseService {

	public $timeout = 5000; // 20 seconds
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
	   		dump($url . " @" . $timeout . " & " . $response->getStatus());
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

	public function get($url){
		$curl = curl_init();
		
		$action_url = $url;



		curl_setopt_array($curl, array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_URL => $url,
			    CURLOPT_USERAGENT => $this->getRandomUserAgent(),
			));
		$resp = curl_exec($curl);


       $result = json_decode($resp, true);

		return $result;		
	}

	public function getRandomUserAgent()
	{
	    $userAgents=array(
	        "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6",
	        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)",
	        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)",
	        "Opera/9.20 (Windows NT 6.0; U; en)",
	        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 8.50",
	        "Mozilla/4.0 (compatible; MSIE 6.0; MSIE 5.5; Windows NT 5.1) Opera 7.02 [en]",
	        "Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; fr; rv:1.7) Gecko/20040624 Firefox/0.9",
	        "Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/48 (like Gecko) Safari/48"       
	    );
	    $random = rand(0,count($userAgents)-1);
	 
	    return $userAgents[$random];
	}
}