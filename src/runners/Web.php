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
    private $widgets;

    public function __construct($params = [])
    {
        parent::__construct($params);
        session_start();
    }

    /**
     * Renders the page
     * @param string $page
     */
    protected function renderPage($page, $widgetInstances)
    {
        $title = isset($page['title']) ? $page['title'] : '';
        $widgets = array_map(function ($w) {
            return $w->render();
        }, $widgetInstances);
        $banner = $this->banner;
        $page_number = $this->pageNumber;

        if (!isset($page['finish'])) {
            if ($page_number > $this->startPage) $show_back = true;
            if ($page_number < $this->getNumberOfPages() - 1) $show_next = true;
        }
        $prev_page_number = $page_number - 1;
        $messages = $_SESSION['messages'] ?? null;
        unset($_SESSION['messages']);

        require __DIR__ . "/../templates/web/main.tpl.php";
    }

    protected function go($params)
    {
        $this->wizard->setData($_SESSION['anyen_data'] ?? []);
        $this->pageNumber = $_SESSION['current_page'] ?? 0;

        if ($this->pageNumber > count($this->wizardDescription)) {
            $this->pageNumber = count($this->wizardDescription) - 1;
        }

        $page = $this->wizardDescription[$this->pageNumber];
        $widgets = $this->loadWidgets($page['widgets'], 'web');
        $this->banner = $page['banner'] ?? $params['banner'];
        $pageAction = filter_input(INPUT_POST, 'page_action');
        $uri = explode('?', filter_input(INPUT_SERVER, 'REQUEST_URI'))[0];
        $render = false;

        if ($pageAction == 'Next') {
            $data = array_merge($this->wizard->getData(), filter_input_array(INPUT_POST));
            unset($data['page_action']);
            $this->wizard->setData($data);
            if ($this->validateWidgets($widgets)) {
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
                $render = true;
            }

        } else if ($pageAction == 'Back') {
            $this->pageNumber--;
            $render = true;
        }

        if ($pageAction) {
            $_SESSION['current_page'] = $this->pageNumber;
            $_SESSION['anyen_data'] = $this->wizard->getData();
            $_SESSION['messages'] = $this->wizard->getMessages();
            header("Location: $uri?$this->pageNumber");
        } else {
            $this->runPage($page, $widgets);
        }
    }
}
