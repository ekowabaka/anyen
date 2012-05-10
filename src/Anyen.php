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

require_once "AnyenCli.php";
require_once "vendor/spyc/spyc.php";

/**
 * Main class for the anyen wizard framework.
 * @author James Ainooson 
 */
abstract class Anyen
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
    protected $data;
    
    /**
     * The status which was set by the last page which was executed.
     * 
     * @var string
     */
    protected $status;
    
    /**
     * A user supplied object which is passed around. It is intended to be used
     * by the user's scripts for their operations.
     * @var mixed
     */
    protected $callbackObject;
    
    /**
     * The main entry point for the anyen framework. Users of the framework
     * call this method to execute their wizards.
     * 
     * @param string $wizardScript A path to the wizard script
     * @param array $params An array of parameters
     * @throws Exception 
     */
    public static function run($wizardScript, $params = array())
    {
        if(defined('STDOUT'))
        {
            $runner = new AnyenCli();
        }
        else
        {
            $runner = new AnyenWeb();
        }
        
        if(file_exists($wizardScript))
        {
            $wizard = Spyc::YAMLLoad(file_get_contents($wizardScript));
        }
        else
        {
            throw new Exception("$wizardScript not found!");
        }
        
        $wizardFunctionsScript = preg_replace("/(\.yml|\.yaml)$/", ".php", $wizardScript);
        
        if(file_exists($wizardFunctionsScript))
        {
            require $wizardFunctionsScript;
        }
        
        for($i = 0; $i < count($wizard); $i++)
        {
            $page = $wizard[$i];
            
            $runner->setCallbackObject($params['callback_object']);
            $runner->executeCallback("{$page['page']}_render_callback");
            $runner->runPage($page);
            $runner->executeCallback("{$page['page']}_route_callback");
            
            switch($runner->getStatus())
            {

                case Anyen::STATUS_REPEAT:
                    $i--;
                    break;
                case Anyen::STATUS_TERMINATE:
                    $i == count($wizard);
                    break;
            }
        }
    }
    
    /**
     * Utility method for executing callbacks.
     * 
     * @param string $callback
     * @return boolean 
     */
    protected function executeCallback($callback)
    {
        if(function_exists($callback))
        {
            $function = new ReflectionFunction($callback);
            return $function->invoke($this);
        }
        return true;
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
        $this->status = Anyen::STATUS_REPEAT;
    }
    
    /**
     * Returns the last status which was set on the wizard
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Returs the data collected so far during the execution of the wizard
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
    
    public function setCallbackObject($object)
    {
        $this->callbackObject = $object;
    }
    
    public function getCallbackObject()
    {
        return $this->callbackObject;
    }
    
    public function runPage($wizard)
    {
        $this->status = Anyen::STATUS_CONTINUE;
        $this->renderPage($wizard);
    }
    
    abstract public function showMessage($message);
    abstract protected function renderPage($wizard);
}

