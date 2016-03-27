<?php
namespace anyen\widgets\cli;

class ChecklistWidget extends \anyen\Widget
{
    public function render() {
        $response = $this->properties['function']($this->wizard);
        echo "\n";
        foreach($response as $check => $value)
        {
            printf("%s%s[%s]\n", $check, str_repeat('.', 75-strlen($check)), $value ? 'yes' : 'no');
        }
    }    
}