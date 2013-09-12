<?php

class WebFunctionWidget extends WebWidget
{
    public function render()
    {
        ob_start();
        $this->wizard->executeCallback($this->properties['function_name'], array(), true);
        return array(
            'html' => ob_get_clean()
        );
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