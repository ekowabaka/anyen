<?php
namespace anyen\widgets\cli;
use anyen\widgets\CliWidget;

class ChecklistWidget extends CliWidget
{
    public function run() {
        $response = $this->wizard->executeCallback($this->properties['function_name'], array());
        echo "\n";
        foreach($response as $check => $value)
        {
            printf("%s%s[%s]\n", $check, str_repeat('.', 75-strlen($check)), $value ? 'yes' : 'no');
        }
    }    
}