<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/19/14
 * Time: 9:07 AM
 */

namespace SwiftReachApi\Voice;

use SwiftReachApi\Exceptions\SwiftReachException;
use SwiftReachApi\Interfaces\JsonSerialize;

class SimpleVoiceMessage implements JsonSerialize
{
    CONST CONTENT_REGEX = '/[^0-9a-zA-Z.:?\' ]/';
    /**
     * Name of the message
     * @var  string
     */
    private $name;

    /**
     * A brief description of what the message is used for
     * @var  string
     */
    private $description;

    /**
     * a 10-digit caller-id that will be sent with the message.  Must be in the format 1234567890
     * @var  string
     */
    private $caller_id;

    /**
     * Auto generates the message content using text-to-speech at the time the message is created.
     * @var  boolean
     */
    private $use_tts = true;

    /**
     * Content of the message
     * @var  string
     */
    private $content;


    /**
     * Voice code that uniquely identifies the voice message once created
     * @var  int
     */
    private $voice_code;


    /**
     * @param $caller_id
     * @return bool
     */
    public function validateCallerId($caller_id)
    {
        // if caller id contains non-numeric values or is not ten digits long, fail
        if(preg_match('/[^0-9]/',$caller_id) || strlen($caller_id) != 10){
            return false;
        }else{
            return true;
        }
    }

    /**
     * @param $content
     * @return bool
     */
    public function validateContent($content)
    {
        // if content contains special characters, fail
        // if content is less than 10 characters
        if(preg_match(self::CONTENT_REGEX, $content) || strlen($content) < 10){
            return false;
        }else{
            return true;
        }
    }


    /**
     * @return string Json Object serialized
     */
    public function toJson()
    {
        $a = array(
            "Name"          => $this->getName(),
            "Description"   => $this->getDescription(),
            "CallerID"      => $this->getCallerId(),
            "UseTTS"        => $this->getUseTts(),
            "Content"       => $this->getContent()
        );

        return json_encode($a);
    }

    /**
     * @param string $caller_id
     */
    public function setCallerId($caller_id)
    {
        if($this->validateCallerId($caller_id) == false){
            throw new SwiftReachException("'".$caller_id."' is not a valid caller id.  Must contain only 10 digits.");
        }

        $this->caller_id = $caller_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCallerId()
    {
        return $this->caller_id;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        if(! $this->validateContent($content)){
            throw new SwiftReachException("The message content contained characters that are not allowed or is shorter than 10 characters");
        }
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param boolean $use_tts
     */
    public function setUseTts($use_tts)
    {
        $this->use_tts = $use_tts;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getUseTts()
    {
        return $this->use_tts;
    }


    /**
     * @param int $voice_code
     */
    public function setVoiceCode($voice_code)
    {
        if(!is_numeric($voice_code)){
            throw new SwiftReachException("'".$voice_code."' must be a numerical value.");
        }

        $this->voice_code = $voice_code;
        return $this;
    }

    /**
     * @return int
     */
    public function getVoiceCode()
    {
        return $this->voice_code;
    }


}