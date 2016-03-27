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
 * Main class for the anyen wizard framework.
 * 
 * @author James Ainooson 
 */
abstract class Runner
{   
    /**
     * Status of a wizard page which requires to be repeated
     */
    const STATUS_REPEAT = 'repeat';
    
    /**
     * Status of a wizard page which needs to be continued
     */
    const STATUS_CONTINUE = 'continue';
    
    /**
     * Status of a wizard page which needs to be terminated 
     */
    const STATUS_TERMINATE = 'end';
    
    /**
     * The data that has been collected by the wizard so far. Every page that
     * is executed returns an associated array which represents the data the
     * user supplied during the execution of the page. These arrays are all
     * merged into this variable.
     * 
     * @var array
     */
    protected $data = array();
    
    /**
     * The status which was set by the last page which was executed.
     * 
     * @var string
     */
    protected $status;
    
    /**
     * A user supplied object which is passed around the wizard. It is intended 
     * to be used by the wizards scripts for their operations.
     * @var mixed
     */
    private $callbackObject;
    
    /**
     * Wizard's name. The name of a given wizard. Names are initially derived
     * from the file name of the wizard script. The wizard script can also
     * explicitly specify a name to be used other than the original filename.
     * @var string 
     */
    protected $name;
    
    protected $wizardDescription;
    
    protected $title;
    
    protected $startPage = 0;
    
    public function __construct($params)
    {
        if(isset($params['callback'])) {
            $this->callbackObject = $params['callback'];
        }
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setWizardDescription($wizard)
    {
        $this->wizardDescription = $wizard;
    }
    
    private static function getRunner($params)
    {
        if(defined('STDOUT'))
        {
            return new runners\Cli($params);
        }
        else
        {
            return new runners\Web($params);
        }        
    }
    
    protected function getBanner($params)
    {
        if(isset($params['banner']))
        {
            return $params['banner'];
        }
        else if(isset($this->wizardDescription[0]['banner']))
        {
            $this->startPage = 1;
            return $this->wizardDescription[0]['banner'];
        }
    }
    
    public function getCallbackObject()
    {
        return $this->callbackObject;
    }

    /**
     * The main entry point for the anyen framework's wizards. Users of the 
     * framework call this method to execute their wizards.
     * 
     * @param script $wizardScript A link to the php script which describes the wizard.
     * @param array $params An array of parameters for the wizard.
     * @throws Exception 
     */
    public static function run($wizardScript, $params = [])
    {   
        $runner = self::getRunner($params);
        if(file_exists($wizardScript))
        {
            $runner->setWizardDescription(require $wizardScript);
        }
        else
        {
            throw new Exception("$wizardScript not found!");
        }            
        $runner->go($params);   
    }
    
    protected function getNumberOfPages()
    {
        return count($this->wizardDescription);
    }
    
    /**
     * Converts an underscore_seperated name and coverts it to a CamelCase one.
     * 
     * @param type $class
     * @return type 
     */
    protected static function getClassName($class)
    {
        $classNameSegments = explode("_", $class);
        $return = '';
        
        foreach($classNameSegments as $nameSegment)
        {
            $return .= ucfirst($nameSegment);
        }
        return $return;
    }
    
    /**
     * Called by the user's wizard functions to set the status of the wizard
     * so it repeats the current page. 
     */
    public function repeatPage()
    {
        $this->status = self::STATUS_REPEAT;
    }
    
    /**
     * Called by the user's wizard functions to set the status of the wizard
     * so the entire wizard is terminated. 
     */
    public function terminate()
    {
        $this->status = self::STATUS_TERMINATE;
    }
    
    /**
     * Returns the last status which was set on the wizard
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }
    
    /**
     * Returs the data collected so far during the execution of the wizard
     * @return array
     */
    public function getData($key = false)
    {
        if($key === false)
        {
            return $this->data;
        }
        else if(is_array($key))
        {
            $respose = [];
            foreach($key as $i)
            {
                $respose[$i] = $this->data[$i];
            }
            return $respose;
        }
        else if(isset($this->data[$key]))
        {
            return $this->data[$key];
        }
    }
    
    public function runPage($wizard)
    {
        $this->status = self::STATUS_CONTINUE;
        $this->renderPage($wizard);
    }
    
    public function resetStatus()
    {
        $this->status = self::STATUS_CONTINUE;
    }
    
    public function loadWidget($widget, $scope)
    {
        $widgetClass = "\\anyen\\widgets\\{$scope}\\" . self::getClassName($widget['type']) . 'Widget';
        $widgetObject = new $widgetClass($widget);
        $widgetObject->setWizard($this);
        return $widgetObject;
    }
    
    abstract public function showMessage($message);
    abstract protected function renderPage($wizard);
    abstract protected function go($params);
}
