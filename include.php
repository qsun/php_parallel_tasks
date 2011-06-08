<?php

function __autoload($class_name) 
{
    require_once 'include/' . strtolower($class_name) . '.class.php';
}
