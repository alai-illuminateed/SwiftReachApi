<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/20/14
 * Time: 9:49 AM
 */

namespace SwiftReachApi\Voice;


use SwiftReachApi\Exceptions\SwiftReachException;
use SwiftReachApi\Interfaces\ArraySerialize;

class VoiceContactPhone implements ArraySerialize{

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $extention = '';

    /**
     * @var int
     */
    private $ans_detection_override = 0;

    /**
     * @var boolean
     */
    private $optin_sms = 0;

    /**
     * @var int
     */
    private $phone_type = 0;

    /**
     * @var string
     */
    private $phone_label;


    function __construct($phone, $phone_label)
    {
        $this->setPhone($phone);
        $this->setPhoneLabel($phone_label);
    }

    public function toArray()
    {
        return array(
            "Phone"                 => $this->getPhone(),
            "Extension"             => $this->getExtention(),
            "AnsDetectionOverride"  => $this->getAnsDetectionOverride(),
            "OptInSMS"              => $this->getOptinSms(),
            "PhoneType"             => $this->getPhoneType(),
            "PhoneLabel"            => $this->getPhoneLabel()
        );
    }


    /**
     * Ensure the phone number is more 10 digits only
     * @param $phone
     * @return bool
     */
    public function validatePhone($phone)
    {
        return ( preg_match('/[^0-9]/',$phone) == 0 && strlen($phone) == 10);
    }


    public function validateAnsDetectionOverride($ans_detection_override)
    {
        return (
            is_numeric($ans_detection_override) &&
            $ans_detection_override >=0 &&
            $ans_detection_override <=2
        );
    }

    /**
     *
     * 0 = Use Default
     * 1 = Always Detect
     * 2 = Never Detect
     *
     * @param int $ans_detection_override
     */
    public function setAnsDetectionOverride($ans_detection_override)
    {
        $this->ans_detection_override = $ans_detection_override;
        if(! $this->validateAnsDetectionOverride($this->getAnsDetectionOverride())){
            throw new SwiftReachException("Ans Detection Override must be a value between 0-2");
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getAnsDetectionOverride()
    {
        return $this->ans_detection_override;
    }

    /**
     * @param string $extention
     */
    public function setExtention($extention)
    {
        $this->extention = $extention;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtention()
    {
        return $this->extention;
    }

    /**
     * @param boolean $optin_sms
     */
    public function setOptinSms($optin_sms)
    {
        $this->optin_sms = $optin_sms;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getOptinSms()
    {
        return $this->optin_sms;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        if(!$this->validatePhone($this->phone)){
            throw new SwiftReachException("'{$phone}' is not a valid phone number.");
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone_label
     */
    public function setPhoneLabel($phone_label)
    {
        $this->phone_label = $phone_label;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneLabel()
    {
        return $this->phone_label;
    }

    /**
     * @param int $phone_type
     */
    public function setPhoneType($phone_type)
    {
        $this->phone_type = $phone_type;
        return $this;
    }

    /**
     * @return int
     */
    public function getPhoneType()
    {
        return $this->phone_type;
    }


} 