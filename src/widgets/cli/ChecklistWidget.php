<?php
namespace anyen\widgets\cli;
use anyen\widgets\CliWidget;

class ChecklistWidget extends CliWidget
{
    public function run() {
        $response = $this->properties['function']($this->wizard);
        echo "\n";
        foreach($response as $check => $value)
        {
            printf("%s%s[%s]\n", $check, str_repeat('.', 75-strlen($check)), $value ? 'yes' : 'no');
        }
    }    
}