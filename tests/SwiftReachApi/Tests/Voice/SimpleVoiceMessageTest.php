<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/19/14
 * Time: 9:51 AM
 */

namespace SwiftReachApi\Tests\Voice;

use SwiftReachApi\Voice\SimpleVoiceMessage;
use SwiftReachApi\Exceptions\SwiftReachException;

class SimpleVoiceMessageTest extends \PHPUnit_Framework_TestCase
{
    public $svm;
    
    public function setup()
    {
        $this->svm = new SimpleVoiceMessage();
    }

    public function testAccessName()
    {
        $test_name = "New simple message";
        $this->svm->setName($test_name);
        $this->assertEquals($test_name, $this->svm->getName());
    }

    public function testAccessDescription()
    {
        $test_description = "This is a short description.";
        $this->svm->setDescription($test_description);
        $this->assertEquals($test_description, $this->svm->getDescription());
    }

    public function testAccessUseTTs()
    {
        // default is true
        $this->assertTrue($this->svm->getUseTts());

        $this->svm->setUseTts(false);
        $this->assertFalse($this->svm->getUseTts());
    }

    public function testCallerIdValidate()
    {
        $this->assertTrue($this->svm->validateCallerId("1234567890"));

        // more than 10 digits
        $this->assertFalse($this->svm->validateCallerId("1234567890123654"));

        // contains non-numeric values
        $this->assertFalse($this->svm->validateCallerId("abc1234567890"));
        $this->assertFalse($this->svm->validateCallerId("123-456-8791"));
    }

    public function testAccessCallerId()
    {
        $test_caller_id = "1234567890";
        $this->svm->setCallerId($test_caller_id);
        $this->assertEquals($test_caller_id, $this->svm->getCallerId());
    }

    /**
     * @expectedException SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testTooLongCallerId()
    {
        $this->svm->setCallerId("1234567890123654");
    }

    /**
     * @expectedException SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testNonNumericCallerId()
    {
        $this->svm->setCallerId("123-456-8791");
    }


    public function testContentValidate()
    {
        $this->assertTrue($this->svm->validateContent("This message is ok."));

        $this->assertFalse($this->svm->validateContent("This message is \"not\" ok @."));

        $this->assertFalse($this->svm->validateContent("Too Short"));
    }

    public function testAccessContent()
    {
        $test_content = "This is a sample message.";
        $this->svm->setContent($test_content);
        $this->assertEquals($test_content, $this->svm->getContent());

    }
    /**
     * @expectedException SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testInvalidCharactersContent()
    {
        $test_content = "This is a bad sample message.&)";
        $this->svm->setContent($test_content);

    }

    /**
     * @expectedException SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testTooShortContent()
    {
        $test_content = "Short";
        $this->svm->setContent($test_content);

    }

    public function testAccessVoiceCode()
    {
        $test_voice_code = "123464";
        $this->svm->setVoiceCode($test_voice_code);
        $this->assertEquals($test_voice_code, $this->svm->getVoiceCode());
    }

    /**
     * @expectedException SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testNonNumericVoiceCode()
    {
        $this->svm->setVoiceCode("abc123");
    }


    public function testToJson()
    {
        $a = array(
            "Name"          => "name",
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


        $this->assertJson($this->svm->toJson());
        $this->assertJsonStringEqualsJsonString($this->svm->toJson(), json_encode($a));
    }

}
 