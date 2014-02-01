<?php

namespace twentysteps\Bundle\PlacetelBundle\Services;

/**
 * Class PlacetelService
 *
 * This service fetches data from the Placetel API
 */
class PlacetelService {

	protected $logger=null;
    protected $cache=null;
    protected $client=null;
    protected $stopwatch=null;

    protected $apiKey=null;
    protected $timeout=null;
    protected $connectTimeout=null;
    protected $cacheTTL=null;

	public function __construct($logger,$cache,$client,$apiKey,$timeout,$connectTimeout,$cacheTTL,$stopwatch=null)
    {
        $this->logger=$logger;
        $this->cache=$cache;
        $this->client=$client;
        $this->stopwatch=$stopwatch;
        $this->apiKey=$apiKey;
        $this->client=$client;
        $this->timeout=$timeout;
        $this->connectTimeout=$connectTimeout;
    }

    public function getIncomingCallsByDay($date) {
    	if ($this->stopwatch) { $this->stopwatch->start('twentysteps\Bundle\Placetel\Services\PlacetelService.getIncomingCallsByDay','20steps'); };
    	$call="getIncomingCallsByDay?apikey=".$this->apiKey;
    	$cacheKey='ts_pt:'.$call;
        $service=null;
        if (false === ($service = $this->cache->fetch($cacheKey))) {
            $this->logger->info('Calling Placetel API: '.$call);
            try {
		        $request = $this->client->get($call,
		            array(), 
		            array(
		                'timeout'         => $this->timeout,
		                'connect_timeout' => $this->connectTimeout
		        ));
		    } catch (\Exception $e) {
		    	$this->logger->info($e->getMessage());
		    	return null;
		    } catch (Guzzle\Http\Exception\CurlException $e){
		    	$this->logger->info($e->getMessage());
		    	return null;
		    }
	        $response=$request->send()->json();
            $this->cache->save($cacheKey, $service, $this->cacheTTL);
        }
    	if ($this->stopwatch) { $this->stopwatch->stop('twentysteps\Bundle\PlacetelBundle\Services\PlacetelService.getService','20steps'); };
        return $service;
    }
    
 }