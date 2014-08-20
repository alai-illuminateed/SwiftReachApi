<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/20/14
 * Time: 11:39 AM
 */

namespace SwiftReachApi\Tests\Voice;


use SwiftReachApi\Voice\VoiceContactPhone;

class VoiceContactPhoneTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var VoiceContactPhone
     */
    public $vcp;

    public function setup()
    {
        $this->vcp = new VoiceContactPhone("5551236478", "home");
    }

    public function testAccessAnsDetection()
    {
        $this->vcp->setAnsDetectionOverride(2);
        $this->assertEquals(2, $this->vcp->getAnsDetectionOverride());
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testNonNumericAnsDetectionOverride()
    {
        $this->vcp->setAnsDetectionOverride("asd");
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testNegativeAnsDetectionOverride()
    {
        $this->vcp->setAnsDetectionOverride(-1);
    }
    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testOverTwoAnsDetectionOverride()
    {
        $this->vcp->setAnsDetectionOverride(4);
    }

    public function testAccessExtention()
    {
        $this->vcp->setExtention(2);
        $this->assertEquals(2, $this->vcp->getExtention());
    }

    public function testAccessPhoneType()
    {
        $this->vcp->setPhoneType(0);
        $this->assertEquals(0, $this->vcp->getPhoneType());
    }

    public function testAccessOptinSms()
    {
        $this->vcp->setOptinSms(false);
        $this->assertFalse($this->vcp->getOptinSms());

        $this->vcp->setOptinSms(true);
        $this->assertTrue($this->vcp->getOptinSms());
    }

    public function testAccessPhone()
    {
        $test_phone = "5551236478";
        $this->vcp->setPhone($test_phone);
        $this->assertEquals($test_phone, $this->vcp->getPhone());
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testNonNumericPhone()
    {
        $this->vcp->setPhone("555-123-6478");
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testTooShortPhone()
    {
        $this->vcp->setPhone("5551234");
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testTooLongPhone()
    {
        $this->vcp->setPhone("5551236958741");
    }
}
 