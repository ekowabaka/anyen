<?php
namespace anyen\widgets\web;
use anyen\Widget;

class TextWidget extends Widget
{    
    public function render() 
    {
        return "<p>{$this->getProperty('text')}</p>";
    }
}
