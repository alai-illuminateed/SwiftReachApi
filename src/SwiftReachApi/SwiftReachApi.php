<?php
namespace SwiftReachApi;

use GuzzleHttp\Client;
use SwiftReachApi\Exceptions\SwiftReachException;
use SwiftReachApi\Voice\SimpleVoiceMessage;
use SwiftReachApi\Voice\VoiceContactArray;

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

        $this->guzzle_client = new Client();
    }

    /**
     * Create a simple voice message on the swift reach system.
     * @param SimpleVoiceMessage $message
     * @return SimpleVoiceMessage Return the passed in $message with the VoiceCode set if the request was successful
     * @throws Exceptions\SwiftReachException
     * @throws \Exception
     */
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
        }
        catch(\Exception $e){
            throw $e;
        }

        $json = $response->json();
        $message->setVoiceCode($json["VoiceCode"]);

        return $message;
    }

    public function sendSimpleVoiceMessageToContactArray(SimpleVoiceMessage $message, VoiceContactArray $contacts)
    {
        if(!$message->getVoiceCode()){
            throw new SwiftReachException("No Voice Code was set.");
        }

        // without a matching name it won't display the caller id number
        // when the call is made, but the call will still go through
        if(!$message->getName()){
            throw new SwiftReachException("The message name was not set or was blank.");
        }
        $url = $this->getBaseUrl()."/api/Messages/Voice/Send/".urlencode($message->getName())."/".$message->getVoiceCode();

        $response = $this->post($url, $contacts->toJson());

        // response body is the job id
        return $response->getBody();
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