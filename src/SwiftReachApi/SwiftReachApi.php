<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/18/14
 * Time: 4:58 PM
 */

namespace SwiftReachApi;

use SwiftReachApi\Exceptions\SwiftReachException;

class SwiftReachApi
{
    private $api_key;
    private $base_url;

    /**
     * @param mixed $base_url
     */
    public function setBaseUrl($base_url)
    {
        if(!filter_var($base_url, FILTER_VALIDATE_URL)){
            throw new SwiftReachException("'".$base_url."' is not a valid url.");
        }
        $this->base_url = $base_url;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->base_url;
    }

    /**
     * @param mixed $api_key
     */
    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

} 