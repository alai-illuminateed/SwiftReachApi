<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/20/14
 * Time: 10:32 AM
 */

namespace SwiftReachApi\Voice;


use SwiftReachApi\Exceptions\SwiftReachException;
use SwiftReachApi\Interfaces\ArraySerialize;
use SwiftReachApi\Interfaces\JsonSerialize;
use SwiftReachApi\Tests\Voice\VoiceContactArrayTest;
use SwiftReachApi\Voice\VoiceContact;

class VoiceContactArray implements JsonSerialize, ArraySerialize{

    /**
     * @var array
     */
    private $contacts = array();

    /**
     * @return string Json Object serialized
     */
    public function toArray()
    {
        $a = array();
        foreach($this->getContacts() as $c){
            /** @var $c VoiceContact  */
            $a[] = $c->toArray();
        }
        return $a;
    }

    /**
     * @return string Json Object serialized
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * @param array $contacts
     */
    public function setContacts($contacts)
    {
        $this->contacts = array();
        foreach($contacts as $vc){
            try{
                $this->addContact($vc);
            }catch(\Exception $e){
                throw $e;
            }

        }
        return $this;
    }

    /**
     * @return array
     */
    public function getContacts()
    {
        return $this->contacts;
    }


    /**
     * @param VoiceContact $vc
     * @return VoiceContactArray
     */
    public function addContact(VoiceContact $vc)
    {
        $this->contacts[] = $vc;
        return $this;
    }

} 