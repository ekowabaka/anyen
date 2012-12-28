<?php

class AnyenWeb extends Anyen
{
    public function showMessage($message)
    {
        
    }
    
    protected function renderPage($wizard)
    {
        if(is_array($page['widgets']))
        {
            foreach($page['widgets'] as $widget)
            {
                $this->renderWidget($widget);
            }
        }        
        require __DIR__ . "/templates/web/main.tpl.php";
    }
    
    private function renderWidget($widget)
    {
        
    }
    
    protected function go($params)
    {
        $wizard = $this->wizardDescription;
        $this->setCallbackObject($params['callback_object']);  
        
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
