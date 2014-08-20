<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/20/14
 * Time: 11:39 AM
 */

namespace SwiftReachApi\Tests\Voice;


use SwiftReachApi\Voice\VoiceContact;
use SwiftReachApi\Voice\VoiceContactPhone;

class VoiceContactTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var VoiceContact
     */
    public $vc;

    /**
     * @var VoiceContactPhone
     */
    public $phone1;

    /**
     * @var VoiceContactPhone
     */
    public $phone2;

    public function setup()
    {
        $this->vc = new VoiceContact("test");

        $this->phone1 = new VoiceContactPhone("5551237890", "home");
        $this->phone2 = new VoiceContactPhone("5553021587", "home");
    }

    public function testAccessName()
    {
        $test_name = 'testname';
        $this->vc->setName($test_name);
        $this->assertEquals($test_name, $this->vc->getName());
    }

    public function testAccessGuid()
    {
        $guid = $this->vc->generateGuid();

        $this->vc->setGuid($guid);
        $this->assertEquals($guid, $this->vc->getGuid());
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testInvalidGuid()
    {
        $this->vc->setGuid("bad-guid");
    }

    public function testToJson()
    {
        $a = array(
            "EntityName" => $this->vc->getName(),
            "EntityGuid" => $this->vc->getGuid(),
            "Phones" => array( $this->phone1->toArray())
        );
        $this->vc->addPhone($this->phone1);

        $this->assertEquals(json_encode($a), $this->vc->toJson());
    }

    public function testAccessPhones()
    {
        $this->vc->setPhones(array($this->phone1));
        $this->assertEquals(1, count($this->vc->getPhones()));

        $this->vc->addPhone($this->phone2);
        $this->assertEquals(2, count($this->vc->getPhones()));

        // make sure it clears the original array
        $this->vc->setPhones(array($this->phone1));
        $this->assertEquals(1, count($this->vc->getPhones()));
    }

    /**
     * @expectedException \Exception
     */
    public function testNonVoiceContactContactAdd()
    {
        $vcp = array("test");
        $this->vc->setPhones(array($vcp));
    }
}
 