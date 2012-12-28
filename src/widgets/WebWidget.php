<?php

abstract class WebWidget 
{
    protected $properties;

    public function __construct($widget)
    {
        $this->properties = $widget;
    }

    public abstract function render();
}
