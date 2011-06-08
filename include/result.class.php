<?php

class Result
{
    var $result;
    
    function __construct($result) 
    {
        $this->result = $result;
    }

    public function freeze() 
    {
        return serialize($this->result);
    }
    
    public static function thaw($resultString) 
    {
        return new Result(unserialize($resultString));
    }
    
    public function  __toString() 
    {
        if (is_object($this->result)) {
            return $this->result->__toString();
        } else {
            return "{$this->result}";
        }
    }
}
