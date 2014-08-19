<?php

namespace SwiftReachApi\Tests;

use SwiftReachApi\SwiftReachApi;

class SwiftReachApiTest extends \PHPUnit_Framework_TestCase {

    public function testAccessApiKey()
    {
        $swift_reach_api = new SwiftReachApi();

        $swift_reach_api->setApiKey("test");
        $this->assertEquals("test", $swift_reach_api->getApiKey());
    }

    public function testAccessBaseUrl()
    {
        $swift_reach_api = new SwiftReachApi();

        $swift_reach_api->setBaseUrl("test");
        $this->assertEquals("test", $swift_reach_api->getBaseUrl());
    }
}
