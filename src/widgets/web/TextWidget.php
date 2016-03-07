<?php
namespace anyen\widgets\web;
use anyen\widgets\WebWidget;

class TextWidget extends WebWidget
{    
    public function render() 
    {
        return "<p>{$this->properties['text']}</p>";
    }
}
