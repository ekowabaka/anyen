<?php
namespace anyen\widgets\web;
use anyen\widgets\WebWidget;

class FunctionWidget extends WebWidget
{
    public function render()
    {
        ob_start();
        $this->properties['function']($this->wizard);
        return ob_get_clean();
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