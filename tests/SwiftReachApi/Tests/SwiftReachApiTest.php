<?php

namespace SwiftReachApi\Tests;

use SwiftReachApi\SwiftReachApi;

class SwiftReachApiTest extends \PHPUnit_Framework_TestCase {

    public function testSetApiKey()
    {
        $swift_reach_api = new SwiftReachApi();

        $swift_reach_api->setApiKey("test");
        $this->assertEquals("test", $swift_reach_api->getApiKey());
    }
}
