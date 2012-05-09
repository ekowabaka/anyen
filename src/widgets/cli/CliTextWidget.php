<?php

class CliTextWidget extends Widget
{
    public function run()
    {
        echo wordwrap($this->properties['text'], 80) . "\n";
    }    
}
