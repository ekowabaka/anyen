<?php

class WebTextWidget extends WebWidget
{    
    public function render() 
    {
        return "<p>{$this->properties['text']}</p>";
    }
}
