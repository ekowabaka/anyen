<?php

namespace anyen\widgets\web;

use anyen\Widget;

class FunctionWidget extends Widget
{
    public function render()
    {
        ob_start();
        $function = $this->getProperty('function');
        $function($this->wizard);
        return ob_get_clean();
    }

}