<?php
namespace anyen\widgets\web;
use anyen\widgets\WebWidget;

class TextWidget extends WebWidget
{    
    public function render() 
    {
        return array(
            'html' => "<p>{$this->properties['text']}</p>"
        );
    }
}
