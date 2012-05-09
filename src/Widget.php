<?php

abstract class Widget
{
    protected $properties;
    
    public function __construct($widgetProperties)
    {
        $this->properties = $widgetProperties;
    }
    
    abstract public function run();
}