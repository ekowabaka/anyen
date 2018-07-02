<?php

namespace anyen\widgets\cli;

use anyen\Widget;

class ChecklistWidget extends Widget
{
    public function render()
    {
        do {
            $response = $this->getProperty('function')($this->wizard);
            $passed = true;
            echo "\n";
            foreach ($response as $check => $value) {
                $passed &= $value;
                printf("%s%s[%s]\n", $check, str_repeat('.', 75 - strlen($check)), $value ? 'yes' : 'no');
            }

            if(!$passed) {
                print "\nPlease ensure that all requirements are met before proceeding ...\n";
                fgetc(STDIN);
            }
        } while(!$passed);
    }
}
