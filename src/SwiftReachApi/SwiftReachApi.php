<?php
namespace SwiftReachApi;

use SwiftReachApi\Exceptions\SwiftReachException;
use SwiftReachApi\Voice\SimpleVoiceMessage;

class SwiftReachApi
{
    /**
     * @var string
     */
    private $api_key;

    /**
     * @var string
     * base url of the swiftreach api witout trailing slash
     * @example http://api.v4.swiftreach.com
     */
    private $base_url;


    /**
     * @var \GuzzleHttp\Client
     */
    private $guzzle_client;

    public function __construct($api_key, $base_url = "http://api.v4.swiftreach.com")
    {
        $this->setApiKey($api_key);
        $this->setBaseUrl($base_url);

        $this->guzzle_client = new \GuzzleHttp\Client();
    }

    public function createSimpleVoiceMessage(SimpleVoiceMessage $message)
    {
        $path = "/api/Messages/Voice/Create/Simple";
        if(!$this->getApiKey()){
            throw new SwiftReachException("Swift Reach Api key was not set.");
        }

        if(!$this->getBaseUrl()){
            throw new SwiftReachException("Base url was not set.");
        }

        try{
            $response = $this->post($this->getBaseUrl().$path, $message->toJson());
        }catch(\Exception $e){
            throw $e;
        }

        $json = $response->json();
        $message->setVoiceCode($json["VoiceCode"]);

        return $message;
    }

    private function post($url, $body)
    {
        $headers = array(
            'Content-type: application/json',
            'SwiftAPI-Key: ' . $this->getApiKey(),
            'Accept: application/json',
            'Expect:'
        );

        try{
            return $this->getGuzzleClient()->post($url, array(
                    'config' => array(
                        'curl' => array(
                            CURLOPT_FOLLOWLOCATION => TRUE,
                            CURLOPT_RETURNTRANSFER => TRUE,
                            CURLOPT_POST => TRUE,
                            CURLOPT_POSTFIELDS => $body,
                            CURLOPT_HTTPHEADER => $headers
                        )
                    )
            ));
        }catch(\Exception $e){
            throw $e;
        }
    }

    /**
     * @param mixed $base_url
     */
    public function setBaseUrl($base_url)
    {
        $this->base_url = $base_url;
        if(!filter_var($base_url, FILTER_VALIDATE_URL)){
            throw new SwiftReachException("'".$base_url."' is not a valid url.");
        }
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

    /**
     * @param \GuzzleHttp\Client $guzzle_client
     */
    public function setGuzzleClient($guzzle_client)
    {
        $this->guzzle_client = $guzzle_client;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getGuzzleClient()
    {
        return $this->guzzle_client;
    }

} 