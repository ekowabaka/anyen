<?php
namespace anyen\widgets\cli;

class FunctionWidget extends \anyen\Widget
{
    public function render()
    {
        $this->properties['function']($this->wizard);
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
