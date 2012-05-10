<?php
class CliFunctionWidget extends Widget
{
    public function run()
    {
        $function = $this->properties['function_name'];
        $function($this);
    }
    
    public function getCallbackObject()
    {
        return $this->wizard->getCallbackObject();
    }
    
    public function getData()
    {
        return $this->wizard->getData();
    }
    
    public function out($message, $progress = false)
    {
        echo "$message\n";
    }
    
    public function error($message)
    {
        fputs(STDERR, $message);
    }
}
