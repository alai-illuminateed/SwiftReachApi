<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/18/14
 * Time: 4:58 PM
 */

namespace SwiftReachApi;


class SwiftReachApi
{
    private $api_key;

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