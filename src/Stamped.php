<?php

namespace onefasteuro\Stamped;

use \Requests;

class Stamped
{
    private $url;

    public function __construct(array $config)
    {
        $this->assertConfig($config);

        $api_key = $config['api_key'];
        $store = $config['store'];

        $this->url = sprintf('https://stamped.io/api/widget/reviews?type=full-page-multiple&apiKey=%s&storeUrl=%s&minrating=1', $api_key, $store);
    }
    

    protected function assertConfig($config)
    {
        if($config['api_key'] == null or $config['store'] == null) {
            throw new StampedException('Missing config parameters');
        }
    }

    public function getStoreReviews($minrating = 4)
    {
        $time = 3600 * 60 * 24;

        $base_url = $this->url;
	
	    $url = $base_url;
	
	    $retry = 0;
	
	    while($retry < 4) {
		    try {
			    $response = \Requests::get($url);
		    }
		    catch(\Requests_Exception $e) {
			    sleep(10);
		    }
		
		    $retry++;
	    }
	
	
	    $output = json_decode($response->body, true);
	
	    unset($output['template']);
	    unset($output['products']);
	    unset($output['lang']);
	
	    return $output;
    }

    public function getProductReviews($product, $limit = 20, $page = 1)
    {
        $base_url = $this->url;
	
	    $url = $base_url . '&productid='.$product . '&take=' . $limit . '&page=' . $page;
	
	    $retry = 0;
	
	    while($retry < 4) {
		    try {
			    $response = \Requests::get($url);
		    }
		    catch(\Requests_Exception $e) {
			    sleep(10);
		    }
		    $retry += 1;
	    }
	
	    $output = json_decode($response->body, true);
	
	    unset($output['template']);
	    unset($output['products']);
	    unset($output['lang']);
	
	    return $output;
    }

}
