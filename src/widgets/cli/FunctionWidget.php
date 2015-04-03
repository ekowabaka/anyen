<?php
namespace anyen\widgets\cli;
use anyen\widgets\CliWidget;

class FunctionWidget extends CliWidget
{
    public function run()
    {
        $this->wizard->executeCallback($this->properties['function_name'], array(), true);
    }
    
    public function getCallbackObject()
    {
        return $this->wizard->getCallbackObject();
    }
    
    public function getData()
    {
        return $this->wizard->getData();
    }
    
    public function error($message)
    {
        fputs(STDERR, $message);
    }
}
