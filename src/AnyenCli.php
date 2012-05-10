<?php
require_once "Widget.php";
require_once "widgets/cli/CliTextInputWidget.php";
require_once "widgets/cli/CliTextWidget.php";
require_once "widgets/cli/CliFunctionWidget.php";

class AnyenCli extends Anyen
{
    private $lastPage = null;
    
    protected function renderPage($page) 
    {
        // Prevent the annoying repeated rendering of the title
        if($page['page'] != $this->lastPage)
        {
            echo "\n{$page['title']}\n";
            echo str_repeat("=", strlen($page['title'])) . "\n";
        }
        $this->lastPage = $page['page'];
        
        if(is_array($page['widgets']))
        {
            foreach($page['widgets'] as $widget)
            {
                $this->renderWidget($widget);
            }
        }
    }
    
    public function showMessage($message)
    {
        echo "$message\n";
    }
    
    private function renderWidget($widget)
    {
        $widgetClass = self::getClassName("cli_{$widget['type']}_widget");
        $widgetObject = new $widgetClass($widget);
        $widgetObject->setWizard($this);
        $response = $widgetObject->run();
        if(is_array($response))
        {
            foreach($response as $key => $value)
            {
                $this->data[$key] = $value;
            }
        }
    }
}
