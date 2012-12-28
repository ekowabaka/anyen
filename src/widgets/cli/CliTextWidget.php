<?php

class CliTextWidget extends CliWidget
{
    public function run()
    {
        echo wordwrap($this->properties['text'], 80) . "\n";
    }    
}
