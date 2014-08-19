<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/19/14
 * Time: 9:12 AM
 */

namespace SwiftReachApi\Interfaces;


interface JsonSerialize {

    /**
     * @return string Json Object serialized
     */
    public function toJson();
} 