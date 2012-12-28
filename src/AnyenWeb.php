<?php

require_once "widgets/WebWidget.php";
require_once "widgets/web/WebTextInputWidget.php";
require_once "widgets/web/WebTextWidget.php";
require_once "widgets/web/WebFunctionWidget.php";

class AnyenWeb extends Anyen
{
    private $banner;
    
    public function showMessage($message)
    {
        
    }
    
    protected function renderPage($page)
    {
        $widgets = array();
        if(is_array($page['widgets']))
        {
            foreach($page['widgets'] as $widget)
            {
                $widgetClass = self::getClassName("web_{$widget['type']}_widget");
                $widgetObject = new $widgetClass($widget);
                $widgets[] = $widgetObject->render();
            }
        }        
        
        $title = $page['title'];
        $banner = $this->banner;
        
        require __DIR__ . "/templates/web/main.tpl.php";
    }
    
    protected function go($params)
    {
        $wizard = $this->wizardDescription;
        $this->setCallbackObject($params['callback_object']);  
        $this->banner = $params['banner'];
        
        if(isset($_GET['p']))
        {
            $i = $_GET['p'];
        }
        else
        {
            $i = 0;
        }
        
        $page = $wizard[$i];

        $this->resetStatus();
        $this->executeCallback("{$page['page']}_render_callback");

        switch($this->getStatus())
        {
            case Anyen::STATUS_REPEAT:
                $i--;
                continue;

            case Anyen::STATUS_TERMINATE:
                $i == count($wizard);
                continue;
        }

        $this->runPage($page);
        
        $this->executeCallback("{$page['page']}_route_callback");

        switch($this->getStatus())
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
