<?php

namespace SwiftReachApi\Tests;

use SwiftReachApi\SwiftReachApi;

class SwiftReachApiTest extends \PHPUnit_Framework_TestCase {

    public $sra;

    public function setup()
    {
        $this->sra = new SwiftReachApi();
    }

    public function testAccessApiKey()
    {

        $this->sra->setApiKey("test");
        $this->assertEquals("test", $this->sra->getApiKey());
    }

    public function testAccessBaseUrl()
    {
        $test_base_url = "http://api.v4.swiftreach.com/api";
        $this->sra->setBaseUrl($test_base_url);
        $this->assertEquals($test_base_url, $this->sra->getBaseUrl());

        $test_base_url = "https://api.v4.swiftreach.com/api";
        $this->sra->setBaseUrl($test_base_url);
        $this->assertEquals($test_base_url, $this->sra->getBaseUrl());
    }

    /**
     * @expectedException SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testInvalidBaseUrl()
    {
        $this->sra->setBaseUrl("test");
    }

    public function testCreateEmailMessage()
    {
    }
}
