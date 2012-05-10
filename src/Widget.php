<?php

abstract class Widget
{
    protected $properties;
    protected $wizard;
    
    public function __construct($widgetProperties)
    {
        $this->properties = $widgetProperties;
    }
    
    public function setWizard($wizard)
    {
        $this->wizard = $wizard;
    }
    
    abstract public function run();
}