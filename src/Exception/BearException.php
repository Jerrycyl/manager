<?php

namespace  cylcode\manager\Exception;
class BearException extends \Exception
{
     public function __construct($message, $code = '-1')
    {
       return returnJson($code,$message);
    }


 
}