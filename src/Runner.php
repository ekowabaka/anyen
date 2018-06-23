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

namespace anyen;

/**
 * Runner is responsible for starting and coordinating the execution of wizards
 *
 * @author James Ainooson
 */
abstract class Runner
{
    /**
     * A description of the entire wizard represented as an array.
     *
     * @var array
     */
    protected $wizardDescription;

    protected $title;

    protected $startPage = 0;

    protected $wizard;

    public function __construct($params = [])
    {
        $this->wizard = new Wizard($params['callback'] ?? null);
    }

    private function setWizardDescription($wizard)
    {
        $this->wizardDescription = $wizard;
    }

    private static function getRunner($params)
    {
        if (defined('STDOUT')) {
            return new runners\Cli($params);
        } else {
            return new runners\Web($params);
        }
    }

    /**
     * The main entry point for the anyen framework's wizards. Users of the
     * framework call this method to execute their wizards.
     *
     * @param array $wizardDescription A link to the php script which describes the wizard.
     * @param array $params An array of parameters for the wizard.
     * @throws \Exception
     */
    public static function run($wizardDescription, $params = [])
    {
        $runner = self::getRunner($params);
        $runner->setWizardDescription($wizardDescription);
        $runner->go($params);
    }

    protected function getNumberOfPages()
    {
        return count($this->wizardDescription);
    }

    /**
     * Converts an underscore_seperated name and coverts it to a CamelCase one.
     *
     * @param string $class
     * @return string
     */
    protected static function getClassName($class)
    {
        $classNameSegments = explode("_", $class);
        $return = '';

        foreach ($classNameSegments as $nameSegment) {
            $return .= ucfirst($nameSegment);
        }
        return $return;
    }

    protected function runPage($wizard)
    {
        $this->wizard->setStatus(Wizard::CONTINUE);
        $this->renderPage($wizard);
    }

    protected function loadWidget($widget, $scope)
    {
        $widgetClass = "\\anyen\\widgets\\{$scope}\\" . self::getClassName($widget['type']) . 'Widget';
        $widgetObject = new $widgetClass($widget, $this->wizard);
        return $widgetObject;
    }

    protected function validateWidgets($widgets)
    {
        $success = true;
        foreach($widgets as $widget) {
            $success &= $widget->validate();
        }
        return $success;
    }

    abstract public function showMessage($message);

    abstract protected function renderPage($wizard);

    abstract protected function go($params);
}
