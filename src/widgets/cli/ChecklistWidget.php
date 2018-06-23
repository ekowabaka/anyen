<?php

namespace anyen\widgets\cli;

use anyen\Widget;

class ChecklistWidget extends Widget
{
    public function render()
    {
        $response = $this->getProperty('function')($this->wizard);
        echo "\n";
        foreach ($response as $check => $value) {
            printf("%s%s[%s]\n", $check, str_repeat('.', 75 - strlen($check)), $value ? 'yes' : 'no');
        }
    }
}
