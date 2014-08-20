<?php

namespace SwiftReachApi\Tests;

use GuzzleHttp\Stream\Stream;
use SwiftReachApi\SwiftReachApi;
use SwiftReachApi\Voice\SimpleVoiceMessage;
use SwiftReachApi\Voice\VoiceContact;
use SwiftReachApi\Voice\VoiceContactArray;
use SwiftReachApi\Voice\VoiceContactPhone;

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

    /** @var  VoiceContactArray */
    public $vca;

    /** @var  VoiceContact */
    public $contact1;

    /** @var  VoiceContact */
    public $contact2;

    public function setup()
    {
        $this->sra = new SwiftReachApi("api-key");

        $this->svm = new SimpleVoiceMessage();
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


        //create contact array
        $this->vca = new VoiceContactArray();

        $this->contact1 = new VoiceContact("Test Tester");
        $contact1_phone = new VoiceContactPhone("5555555555","home");
        $this->contact1->setPhones(array($contact1_phone));

        $this->contact2 = new VoiceContact("two phone test");
        $contact2_phone1 = new VoiceContactPhone("5555555555","home");
        $contact2_phone2 = new VoiceContactPhone("5555555555","home");
        $this->contact2->setPhones(array($contact2_phone1, $contact2_phone2));

        $this->vca->addContact($this->contact1)->addContact($this->contact2);
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

    //------------------------------------------------------------------------------------------------

    private function createMockForSuccessfullSimpleVoiceSending()
    {
        $mock = new Mock(array(
            new Response(200, array(), Stream::factory('123456789'))
        ));
        $client = new Client();
        $client->getEmitter()->attach($mock);
        $this->sra->setGuzzleClient($client);
    }

    public function testSendCreateSimpleVoiceMessage()
    {
        //set up mock
        $this->createMockForSuccessfullSimpleVoiceSending();

        // add voice code from previously created voice code
        $this->svm->setVoiceCode("123456");

        $job_id = $this->sra->sendSimpleVoiceMessageToContactArray($this->svm, $this->vca);
    }

    /**
     * @expectedException SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testMissingVoiceCodeSendCreateSimpleVoiceMessage()
    {
        //set up mock
        $this->createMockForSuccessfullSimpleVoiceSending();

        $job_id = $this->sra->sendSimpleVoiceMessageToContactArray($this->svm, $this->vca);

    }


    /**
     * @expectedException SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testNoNameSendCreateSimpleVoiceMessage()
    {
        //set up mock
        $this->createMockForSuccessfullSimpleVoiceSending();

        // add voice code from previously created voice code
        $svm = new SimpleVoiceMessage();

        $svm->setVoiceCode("123456");

        $job_id = $this->sra->sendSimpleVoiceMessageToContactArray($svm, $this->vca);

    }

}
