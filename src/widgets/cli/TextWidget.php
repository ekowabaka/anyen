<?php
namespace anyen\widgets\cli;

class TextWidget extends \anyen\Widget
{
    public function render()
    {
        echo wordwrap($this->getProperty('text'), 80) . "\n";
    }    
}
