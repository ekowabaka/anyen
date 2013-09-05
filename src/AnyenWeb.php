<?php

session_start();

require_once "widgets/WebWidget.php";
require_once "widgets/web/WebTextInputWidget.php";
require_once "widgets/web/WebTextWidget.php";
require_once "widgets/web/WebFunctionWidget.php";
require_once "widgets/web/WebChecklistWidget.php";

class AnyenWeb extends Anyen
{
    private $banner;
    private $pageNumber;
    private $hash;
    private $message;
    
    public function showMessage($message)
    {
        $this->message = $message;
    }
    
    /**
     * Generates a unique hash for the page. This is used in ensuring that
     * the user hasn't jumped any steps in the progress of the wizard by
     * altering the ?p attribute in the URL query.
     * 
     * @todo this method should use a stronger hash with some randomized values
     * @param string $pageNumber
     * @return string
     */
    private function getHash($pageNumber)
    {
        $hash = '';
        for($i = 0; $i <= $pageNumber; $i++)
        {
            $hash .= $i . $this->wizardDescription[$i]['title'];
        }
        return md5($hash);
    }
    
    /**
     * Renders the page 
     * @param string $page
     */
    protected function renderPage($page)
    {
        $widgets = array();
        if(is_array($page['widgets']))
        {
            foreach($page['widgets'] as $widget)
            {
                $widgets[] = $this->loadWidget($widget, 'web')->render();
            }
        }        
        
        $title = $page['title'];
        $banner = $this->banner;
        $page_number = $this->pageNumber;
        $hash = $this->hash;
        $show_next = true;
        //if($page_number > 1) $show_back = true;
        $message = $_GET['m'];
        
        require __DIR__ . "/templates/web/main.tpl.php";
    }
    
    protected function go($params)
    {
        $wizard = $this->wizardDescription;
        $this->setCallbackObject($params['callback_object']);  
        $this->banner = $params['banner'];
                
        if(isset($_GET['p']))
        {
            $this->pageNumber = $_GET['p'];
        }
        else
        {
            $this->pageNumber = 0;
            $_SESSION['anyen_data'] = array();
        }
        
        $this->hash = $this->getHash($this->pageNumber);        
        $page = $wizard[$this->pageNumber];
        
        /* $_GET['h'] represents the hash 
         * $_GET['p'] represents the page number
         * $_GET['a'] is a constant used to prevent a redirect loop
         */
        if($this->hash == $_GET['h'] && $this->pageNumber == $_GET['p'] && $_GET['a'] == 'n')
        {
            foreach(explode('&', $_GET['d']) as $attrBlock)
            {
                $attr = explode('=', $attrBlock);
                $_SESSION['anyen_data'][$attr[0]] = $attr[1];
            }

            $this->data = unserialize($_SESSION['logic_object_data']);
            $this->executeCallback("{$page['page']}_route_callback");
            
            switch($this->getStatus())
            {
                case Anyen::STATUS_REPEAT:
                    $extraQuery = "&m=" . urlencode($this->message) . '&' . $_GET['d'] ;
                    break;

                case Anyen::STATUS_TERMINATE:
                    $this->pageNumber = count($wizard);
                    break;
                
                default:
                    $this->pageNumber++;
            }
            $newHash = $this->getHash($this->pageNumber);
            header("Location: ?p={$this->pageNumber}&h={$newHash}" . $extraQuery);
        }        

        $this->resetStatus();
        $this->executeCallback("{$page['page']}_render_callback");

        switch($this->getStatus())
        {
            case Anyen::STATUS_REPEAT:
                // Do nothing
                continue;

            case Anyen::STATUS_TERMINATE:
                $this->pageNumber = count($wizard);
                continue;
        }

        $this->runPage($page);
        $_SESSION['logic_object_data'] = serialize($this->data);        
    }
}
