<?php

class Queue 
{
    var $pending = array();
    
    function enqueue(&$obj) 
    {
        array_push($this->pending, $obj);
    }

    function dequeue() 
    {
        return array_shift($this->pending);
    }
    
}
