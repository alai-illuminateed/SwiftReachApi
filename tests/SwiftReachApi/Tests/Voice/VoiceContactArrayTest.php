<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/20/14
 * Time: 11:39 AM
 */

namespace SwiftReachApi\Tests\Voice;


use SwiftReachApi\Voice\VoiceContact;
use SwiftReachApi\Voice\VoiceContactArray;

class VoiceContactArrayTest extends \PHPUnit_Framework_TestCase {

    /** @var  VoiceContactArray */
    public $vca;

    /** @var  VoiceContact */
    public $contact1;

    /** @var  VoiceContact */
    public $contact2;

    public function setup()
    {
        $this->vca = new VoiceContactArray();

        $this->contact1 = new \SwiftReachApi\Voice\VoiceContact("Test Tester");
        $contact1_phone = new \SwiftReachApi\Voice\VoiceContactPhone("5555555555","home");
        $this->contact1->setPhones(array($contact1_phone));


        $this->contact2 = new \SwiftReachApi\Voice\VoiceContact("two phone test");
        $contact2_phone1 = new \SwiftReachApi\Voice\VoiceContactPhone("5555555555","home");
        $contact2_phone2 = new \SwiftReachApi\Voice\VoiceContactPhone("5555555555","home");
        $this->contact2->setPhones(array($contact2_phone1, $contact2_phone2));
    }


    public function testAccessContacts()
    {
        $this->vca->setContacts(array($this->contact1));
        $this->assertEquals(1, count($this->vca->getContacts()));

        $this->vca->addContact($this->contact2);
        $this->assertEquals(2, count($this->vca->getContacts()));

        // make sure it clears the original array
        $this->vca->setContacts(array($this->contact1));
        $this->assertEquals(1, count($this->vca->getContacts()));
    }

    /**
     * @expectedException \Exception
     */
    public function testNonVoiceContactContactAdd()
    {
        $vc = array("test");
        $this->vca->setContacts(array($vc));
    }


    public function testToJson()
    {
        $a = array(
            $this->contact1->toArray(),
            $this->contact2->toArray(),
        );
        $this->vca->setContacts(array($this->contact1, $this->contact2));
        $this->assertEquals(json_encode($a), $this->vca->toJson());
    }
}

