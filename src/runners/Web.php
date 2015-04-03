<?php
namespace anyen\runners;

use anyen\Runner;

session_start();

class Web extends Runner
{
    private $banner;
    private $pageNumber;
    private $hash;
    
    public function showMessage($message, $type = 'info')
    {
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $type;
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
        if($page_number > 0) $show_back = true;
        if($page_number < $this->getNumberOfPages() - 1) $show_next = true;
        $prev_page_number = $page_number - 1;
        $message = $_SESSION['message'];
        $message_type = $_SESSION['message_type'];
        unset($_SESSION['message']);
        
        require __DIR__ . "/../templates/web/main.tpl.php";
    }
    
    protected function go($params)
    {
        $wizard = $this->wizardDescription;
        $this->setCallbackObject($params['callback_object']);  
        $this->banner = $params['banner'];
        $this->data = isset($_SESSION['anyen_data']) ? unserialize($_SESSION['anyen_data']) : array();
                
        if($_GET['p'] != '')
        {
            $this->pageNumber = $_GET['p'];
        }
        else
        {
            $this->pageNumber = 0;
        }
        
        $this->hash = $this->getHash($this->pageNumber);        
        $page = $wizard[$this->pageNumber];
        
        /* $_GET['h'] represents the hash 
         * $_GET['p'] represents the page number
         * $_GET['a'] is a constant used to prevent a redirect loop
         */
        if($this->hash == $_GET['h'] && $this->pageNumber == $_GET['p'] && $_GET['a'] == 'n')
        {            
            parse_str($_GET['d'], $this->data);
            $this->data = array_merge(unserialize($_SESSION['anyen_data']), $this->data);
            
            $this->executeCallback("{$page['page']}_route_callback");
            
            switch($this->getStatus())
            {
                case Runner::STATUS_REPEAT:
                    break;

                case Runner::STATUS_TERMINATE:
                    $this->pageNumber = count($wizard);
                    break;
                
                default:
                    $this->pageNumber++;
            }
            $newHash = $this->getHash($this->pageNumber);
            $_SESSION['anyen_data'] = serialize($this->data);                    
            header("Location: ?p={$this->pageNumber}&h={$newHash}" . $extraQuery);
            return;
        }        

        $this->resetStatus();
        $this->executeCallback("{$page['page']}_render_callback");

        switch($this->getStatus())
        {
            case Runner::STATUS_REPEAT:
                // Do nothing
                continue;

            case Runner::STATUS_TERMINATE:
                $this->pageNumber = count($wizard);
                continue;
        }

        $this->runPage($page);
        $_SESSION['anyen_data'] = serialize($this->data);        
    }
}
