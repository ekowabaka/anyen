<?php
namespace anyen\widgets\cli;

class FunctionWidget extends \anyen\Widget
{
    public function render()
    {
        $this->getProperty('function')($this->wizard);
    }
}
