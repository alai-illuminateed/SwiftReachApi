<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/19/14
 * Time: 5:29 PM
 */

namespace SwiftReachApi\Voice;


use SwiftReachApi\Interfaces\ArraySerialize;
use SwiftReachApi\Interfaces\JsonSerialize;
use SwiftReachApi\Exceptions\SwiftReachException;

class VoiceContact implements JsonSerialize, ArraySerialize
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $guid;

    /**
     * @var array
     */
    private $phones;

    function __construct($name, $guid ='')
    {
        $this->setName($name);
        $this->phones = array();

        // if guid isn't set generate it
        if($guid == ''){
            $this->setGuid($this->generateGuid());
        }
    }

    /**
     * generate a unique id for a
     *
     * @return string
     * @link http://php.net/manual/en/function.com-create-guid.php
     */
    public function generateGuid()
    {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
        return $uuid;
    }

    /**
     * @return string Json Object serialized
     */
    public function toArray()
    {
        $a = array(
            "EntityName" => $this->getName(),
            "EntityGuid" => $this->getGuid(),
            "Phones" => array()
        );

        // add the phones
        foreach($this->getPhones() as $p){
            /** @var $p VoiceContactPhone */
            $a["Phones"][] = $p->toArray();
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

    public function validateGuid($guid)
    {
        return preg_match('/^[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}$/', $guid) == 1;
    }

    /**
     * @param string $guid
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
        if(!$this->validateGuid($this->getGuid())){
            throw new SwiftReachException("'".$this->getGuid()."' is not a valid GUID");
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
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
     * @param array $phones
     */
    public function setPhones($phones)
    {
        $this->phones = array();
        foreach($phones as $p){
            try{
                $this->addPhone($p);
            }catch(\Exception $e){
                throw $e;
            }
        }
        return $this;
    }

    public function addPhone(VoiceContactPhone $phone)
    {
        $this->phones[] = $phone;
        return $this;
    }

    /**
     * @return array
     */
    public function getPhones()
    {
        return $this->phones;
    }

}