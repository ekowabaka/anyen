<?php
namespace anyen\widgets\cli;
use anyen\widgets\CliWidget;

class TextWidget extends CliWidget
{
    public function run()
    {
        echo wordwrap($this->properties['text'], 80) . "\n";
    }    
}
