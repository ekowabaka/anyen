<?php
class CliFunctionWidget extends CliWidget
{
    public function run()
    {
        $this->wizard->executeCallback($this->properties['function_name'], array());
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
