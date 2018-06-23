<?php

namespace anyen\runners;

use anyen\Runner;
use anyen\Wizard;

/**
 * Class Web
 *
 * @package anyen\runners
 */
class Web extends Runner
{
    private $banner;
    private $pageNumber;

    public function __construct($params = [])
    {
        parent::__construct($params);
        session_start();
    }

    /**
     * Renders the page
     * @param string $page
     */
    protected function renderPage($page)
    {
        $widgets = array();
        foreach (isset($page['widgets']) ? $page['widgets'] : [] as $widget) {
            $widgets[] = $this->loadWidget($widget, 'web')->render();
        }

        $title = isset($page['title']) ? $page['title'] : '';
        $banner = $this->banner;

        $page_number = $this->pageNumber;

        if (!isset($page['finish'])) {
            if ($page_number > $this->startPage) $show_back = true;
            if ($page_number < $this->getNumberOfPages() - 1) $show_next = true;
        }

        $prev_page_number = $page_number - 1;
        $message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
        $message_type = isset($_SESSION['message_type']) ? $_SESSION['message'] : null;
        unset($_SESSION['message']);

        require __DIR__ . "/../templates/web/main.tpl.php";
    }

    protected function go($params)
    {
        $this->wizard->setData($_SESSION['anyen_data'] ?? []);
        $this->pageNumber = $_SESSION['current_page'];

        if($this->pageNumber > count($this->wizardDescription)) {
            $this->pageNumber = count($this->wizardDescription) - 1;
        }

        $page = $this->wizardDescription[$this->pageNumber];
        $this->banner = $page['banner'] ?? $params['banner'];
        $pageAction = filter_input(INPUT_POST, 'page_action');
        $uri = filter_input(INPUT_SERVER, 'REQUEST_URI');

        if ($pageAction == 'Next') {
            $data = array_merge($this->wizard->getData(), filter_input_array(INPUT_POST));
            unset($data['page_action']);
            if($this->validateWidgets($page['widgets'])) {
                if (isset($page['on_next'])) {
                    $page['on_next']($this->wizard);
                }
                switch ($this->wizard->getStatus()) {
                    case Wizard::REPEAT:
                        break;
                    case Wizard::TERMINATE:
                        $this->pageNumber = count($this->wizardDescription);
                        break;
                    default:
                        $this->pageNumber++;
                }
            }
            $_SESSION['anyen_data'] = $this->wizard->getData();
        } else if ($pageAction == 'Back') {
            $this->pageNumber--;
        }

        if ($pageAction) {
            $_SESSION['current_page'] = $this->pageNumber;
            header("Location: $uri");
        } else {
            $this->runPage($page);
        }
    }
}
