<?php
require_once "AnyenCli.php";
require_once "vendor/spyc/spyc.php";

/**
 * Main class for the anyen framework.
 * @author James Ainooson 
 */
abstract class Anyen
{   
    const STATUS_REPEAT = 'repeat';
    const STATUS_CONTINUE = 'continue';
    const STATUS_TERMINATE = 'end';
    
    protected $data;
    
    protected $status;
    
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
    
    protected function executeCallback($callback)
    {
        if(function_exists($callback))
        {
            $function = new ReflectionFunction($callback);
            return $function->invoke($this);
        }
        return true;
    }
    
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
    
    public function repeatPage()
    {
        $this->status = Anyen::STATUS_REPEAT;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function runPage($wizard)
    {
        $this->status = Anyen::STATUS_CONTINUE;
        $this->renderPage($wizard);
    }
    
    abstract public function showMessage($message);
    abstract protected function renderPage($wizard);
}

