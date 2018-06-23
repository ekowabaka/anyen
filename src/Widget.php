<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace anyen;

/**
 * Description of Widget
 *
 * @author ekow
 */
abstract class Widget
{
    /**
     * The properties which were passed to the widget by the runner. Properties
     * are mostly defined in the yaml files which are used to describe the
     * wizard.
     *
     * @var array
     */
    private $properties;

    /**
     * An instance of the wizard which is currently running.
     *
     * @var Wizard
     */
    protected $wizard;

    public function __construct($widgetProperties, $wizard)
    {
        $this->properties = $widgetProperties;
        $this->wizard = $wizard;
    }

    protected function getProperty($key)
    {
        if (isset($this->properties[$key])) {
            return $this->properties[$key];
        }
    }

    protected function setProperties($properties)
    {
        $this->properties = $properties;
    }

    /**
     * Set the wizard that this widget is associated with. This is normally called
     * by the runner.
     *
     * @return  Wizard
     */
    public function getWizard()
    {
        return $this->wizard;
    }

    public abstract function render();

    public function validate()
    {
        return true;
    }
}
