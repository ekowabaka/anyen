<?php

class WebTextWidget extends WebWidget
{    
    public function render() 
    {
        return array(
            'html' => "<p>{$this->properties['text']}</p>"
        );
    }
}
