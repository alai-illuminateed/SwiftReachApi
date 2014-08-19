<?php

namespace SwiftReachApi\Tests;

use GuzzleHttp\Stream\Stream;
use SwiftReachApi\SwiftReachApi;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;

class SwiftReachApiTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var SwiftReachApi
     */
    public $sra;

    /**
     * @var \SwiftReachApi\Voice\SimpleVoiceMessage
     */
    public $svm;

    public function setup()
    {
        $this->sra = new SwiftReachApi("api-key");

        $this->svm = new \SwiftReachApi\Voice\SimpleVoiceMessage();
        $a = array(
            "Name"          => "API test message",
            "Description"   => "description",
            "CallerID"      => 1234567890,
            "UseTTS"        => true,
            "Content"       => "content that must be at least 10 characters long."
        );

        $this->svm->setName($a["Name"])
            ->setDescription($a["Description"])
            ->setCallerId($a["CallerID"])
            ->setUseTTS($a["UseTTS"])
            ->setContent($a["Content"]);
    }

    public function testConstructor()
    {
        $key = "api-key";
        $sra = new SwiftReachApi($key);
        $this->assertEquals($key, $sra->getApiKey());
        $this->assertEquals("http://api.v4.swiftreach.com", $sra->getBaseUrl());


        $base_url = "http://www.example.com";
        $sra = new SwiftReachApi($key, $base_url);
        $this->assertEquals($key, $sra->getApiKey());
        $this->assertEquals($base_url, $sra->getBaseUrl());
    }

    public function testAccessApiKey()
    {
        $this->sra->setApiKey("test");
        $this->assertEquals("test", $this->sra->getApiKey());
    }

    public function testAccessBaseUrl()
    {
        $test_base_url = "http://api.v4.swiftreach.com";
        $this->sra->setBaseUrl($test_base_url);
        $this->assertEquals($test_base_url, $this->sra->getBaseUrl());
    }

    public function testAccessGuzzleClient()
    {
        $client = new Client();
        $this->sra->setGuzzleClient($client);
        $this->assertInstanceOf('GuzzleHttp\Client', $this->sra->getGuzzleClient());
    }

    /**
     * @expectedException SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testInvalidBaseUrl()
    {
        $this->sra->setBaseUrl("test");
    }

    public function testCreateSimpleVoiceMessage()
    {
        //set up mock
        $mock = new Mock(array(
            new Response(200, array(), Stream::factory('{"AutoReplays":1,"RequireResponse":false,"ValidResponses":"","EnableAnsweringMachineMessage":false,"ContentProfile":[{"SpokenLanguage":"English","TTY_Text":null,"VoiceItem":[{"VoiceItemType":1,"AudioSource":[{"$type":"SwiftReach.Swift911.Core.Messages.Voice.AUDIO_SOURCE_VOICE, SwiftReach.Swift911.Core","VoiceCode":102746,"Content":"content that must be at least 10 characters long.","AutoGenerateVoice":true,"FileVersion":0,"AudioType":0}]}]}],"DefaultSpokenLanguage":"English","CallerID":"2012361344","CapacityLimit":0,"RingSeconds":60,"CongestionAttempts":2,"AutoRetries":1,"AutoRetriesInterval":3,"EnableWaterfall":false,"EnableAnsweringMachineDetection":false,"CreateStamp":"0001-01-01T00:00:00","ChangeStamp":"0001-01-01T00:00:00","LastUsed":"0001-01-01T00:00:00","CreatedByUser":null,"ChangedByUser":null,"Name":"API test message","Description":"description","VoiceCode":2304376,"VoiceType":14,"Visibility":0,"DeleteLocked":false}'))
        ));
        $client = new Client();
        $client->getEmitter()->attach($mock);

        $this->sra->setGuzzleClient($client);

        $svm = $this->sra->createSimpleVoiceMessage($this->svm);

        $this->assertInstanceOf('\SwiftReachApi\Voice\SimpleVoiceMessage', $this->svm);
        $this->assertTrue(is_numeric($svm->getVoiceCode()));
        $this->assertEquals("2304376", $svm->getVoiceCode());
    }

    /**
     * @expectedException Exception
     */
    public function test405ErrorCreateSimpleVoiceMessage()
    {
        //set up mock
        $mock = new Mock(array(
            new Response(405, array())
        ));
        $client = new Client();
        $client->getEmitter()->attach($mock);

        $this->sra->setGuzzleClient($client);

        $svm = $this->sra->createSimpleVoiceMessage($this->svm);
    }

    /**
     * @expectedException SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testMissingApiKeyCreateSimpleVoiceMessage()
    {
        try{
            $this->sra->setApiKey('');
        }catch(\Exception $e){

        }

        $svm = $this->sra->createSimpleVoiceMessage($this->svm);
    }

    /**
     * @expectedException SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testMissingBaseUrlCreateSimpleVoiceMessage()
    {
        try{
            $this->sra->setBaseUrl(false);
        }catch(\SwiftReachApi\Exceptions\SwiftReachException $e){

        }

        $svm = $this->sra->createSimpleVoiceMessage($this->svm);
    }

}
