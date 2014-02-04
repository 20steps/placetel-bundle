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

    public function getIncomingCallsByDay($date=null) {
    	if ($this->stopwatch) { $this->stopwatch->start('twentysteps\Bundle\Placetel\Services\PlacetelService.getIncomingCallsByDay','20steps'); };
        if ($date==null) {
            $date=new \DateTime();
        }
    	$call="getIncomingCallsByDay.json";
    	$cacheKey='ts_pt:calls/incoming/'.$date->format('d.m.Y.');
        $response=null;
        if (false === ($response = $this->cache->fetch($cacheKey))) {
            $this->logger->info('Calling Placetel API: '.$call);
            try {
		        $request = $this->client->post($call,
		            array(),
                    array(
                        'api_key' => $this->apiKey,
                        'year' => $date->format('Y'),
                        'month' => $date->format('m'),
                        'day' => $date->format('d')
                    ),
		            array(
		                'timeout'         => $this->timeout,
		                'connect_timeout' => $this->connectTimeout
		        ));
                $response=$request->send()->json();
		    } catch (\Exception $e) {
		    	$this->logger->info($e->getMessage());
		    	return null;
		    } catch (Guzzle\Http\Exception\CurlException $e){
		    	$this->logger->info($e->getMessage());
		    	return null;
		    }
            // due to rate limit
            sleep(2);
            $this->cache->save($cacheKey, $response, $this->cacheTTL);
        }
    	if ($this->stopwatch) { $this->stopwatch->stop('twentysteps\Bundle\PlacetelBundle\Services\PlacetelService.getService','20steps'); };
        return $response;
    }

    public function getIncomingCallsCountByDay($date=null,$callTypeFilter=null,$toNumber=null) {
        $calls=$this->getIncomingCallsByDay($date);
        if ($calls && is_array($calls)) {
            $count=0;
            foreach ($calls as $call) {
                if ($callTypeFilter && $call['callType']!=$callTypeFilter) {
                    continue;
                }
                if ($toNumber && $call['toNumber']!=$toNumber) {
                    continue;
                }
                $count++;
            }
            return $count;
        }
        return 0;
    }
    
 }