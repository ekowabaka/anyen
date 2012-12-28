<?php
/**
 * Copyright (c) 2012 James Ekow Abaka Ainooson
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 * 
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * 
 */

/**
 * An abstract cli widget class. Widgets are used by the various runners to provide
 * interface elements for user interaction.
 * 
 * @author James Ainooson <jainooson@gmail.com> 
 */
abstract class CliWidget
{
    /**
     * The properties which were passed to the widget by the runner. Properties
     * are mostly defined in the yaml files which are used to describe the
     * wizard.
     * 
     * @var array
     */
    protected $properties;
    
    /**
     * An instance of the wizard which is currently running.
     * 
     * @var Anyen
     */
    protected $wizard;
    
    public function __construct($widgetProperties)
    {
        $this->properties = $widgetProperties;
    }
    
    /**
     * Set the wizard that this widget is associated with. This is normally called
     * by the runner.
     * 
     * @param type $wizard 
     */
    public function setWizard($wizard)
    {
        $this->wizard = $wizard;
    }
    
    /**
     * Called by the runner to execute a given widget. 
     */
    abstract public function run();
}