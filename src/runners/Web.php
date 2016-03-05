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
            $hash .= $i . isset($this->wizardDescription[$i]['title']) ? $this->wizardDescription[$i]['title'] : null;
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
        foreach(isset($page['widgets']) ? $page['widgets'] : [] as $widget)
        {
            $widgets[] = $this->loadWidget($widget, 'web')->render();
        }
        
        $title = isset($page['title']) ? $page['title'] : '';
        $banner = $this->banner;
        $page_number = $this->pageNumber;
        $hash = $this->hash;
        if($page_number > $this->startPage) $show_back = true;
        if($page_number < $this->getNumberOfPages() - 1) $show_next = true;
        $prev_page_number = $page_number - 1;
        $message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
        $message_type = isset($_SESSION['message_type']) ? $_SESSION['message'] : null;
        unset($_SESSION['message']);
        
        require __DIR__ . "/../templates/web/main.tpl.php";
    }
    
    protected function go($params)
    {
        $wizard = $this->wizardDescription;
        if(isset($params['callback_object']))
        {
            $this->setCallbackObject($params['callback_object']);  
        }
        $this->banner = $this->getBanner($params);
        $this->data = isset($_SESSION['anyen_data']) ? unserialize($_SESSION['anyen_data']) : array();
        
        $currentPage = filter_input(INPUT_GET, 'p');
        $hash = filter_input(INPUT_GET, 'h');
        $loopBlocker = filter_input(INPUT_GET, 'a');
                
        if($currentPage != '')
        {
            $this->pageNumber = $currentPage;
        }
        else
        {
            $this->pageNumber = $this->startPage;
        }
        
        $this->hash = $this->getHash($this->pageNumber);        
        $page = $wizard[$this->pageNumber];

        // We are moving to another page
        if($this->hash == $hash && $this->pageNumber == $currentPage && $loopBlocker == 'n')
        {            
            parse_str(filter_input(INPUT_GET, 'd'), $this->data);
            $this->data = array_merge(unserialize($_SESSION['anyen_data']), $this->data);
            
            if(isset($page['onroute']))
            {
                $page['onroute']($this);
            }
            
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
            header("Location: ?p={$this->pageNumber}&h={$newHash}");
            return;
        }        

        $this->resetStatus();
        if(isset($page['onrender']))
        {
            $page['onrender']($this);
        }

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
