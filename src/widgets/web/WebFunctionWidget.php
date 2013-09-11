<?php

class WebFunctionWidget extends WebWidget
{
    public function render()
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