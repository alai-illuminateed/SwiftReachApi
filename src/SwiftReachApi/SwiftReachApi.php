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
    private $base_url;

    /**
     *
     */
    public function createVoiceMessage()
    {

    }

    /**
     * @summary Create an email message
     * @return email id
     */
    public function createEmailMessage()
    {

    }




    /**
     * @param mixed $base_url
     */
    public function setBaseUrl($base_url)
    {
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