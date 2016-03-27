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
        
        if(!isset($page['finish'])) {
            if($page_number > $this->startPage) $show_back = true;
            if($page_number < $this->getNumberOfPages() - 1) $show_next = true;
        }
        
        $prev_page_number = $page_number - 1;
        $message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
        $message_type = isset($_SESSION['message_type']) ? $_SESSION['message'] : null;
        unset($_SESSION['message']);
        
        require __DIR__ . "/../templates/web/main.tpl.php";
    }
    
    protected function go($params)
    {
        $wizard = $this->wizardDescription;
        $this->banner = $this->getBanner($params);
        $this->data = isset($_SESSION['anyen_data']) ? $_SESSION['anyen_data'] : array();
        
        $currentPage = $_SESSION['current_page'];
                
        if($currentPage != '')
        {
            $this->pageNumber = $currentPage;
        }
        else
        {
            $this->pageNumber = $this->startPage;
        }
        
        $page = $wizard[$this->pageNumber];
        
        if(filter_input(INPUT_POST, 'page_action') == 'Next')
        {
            $data = filter_input_array(INPUT_POST);
            unset($data['page_action']);
            $this->data = array_merge($this->data, $data);
            if(isset($page['onroute']))
            {
                $page['onroute']($this);
            }            
            switch($this->getStatus())
            {
                case Runner::STATUS_REPEAT: break;
                case Runner::STATUS_TERMINATE:
                    $this->pageNumber = count($wizard);
                    break;
                default: $this->pageNumber++;
            }
            
            $_SESSION['anyen_data'] = $this->data;
            $_SESSION['current_page'] = $this->pageNumber;
            header("Location: ./");
            return;
        }
        else if(filter_input(INPUT_POST, 'page_action') == 'Back')
        {
            $this->pageNumber--;
            $_SESSION['current_page'] = $this->pageNumber;
            header("Location: ./");
            return;
        }        
        
        // We are moving to another page
        /*if($this->hash == $hash && $this->pageNumber == $currentPage && $loopBlocker == 'n')
        {            
            parse_str(filter_input(INPUT_GET, 'd'), $this->data);
            $this->data = array_merge($_SESSION['anyen_data'], $this->data);
            
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
            $_SESSION['anyen_data'] = $this->data;
            header("Location: ?p={$this->pageNumber}&h={$newHash}");
            return;
        }*/

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
        $_SESSION['anyen_data'] = $this->data;        
    }
}
