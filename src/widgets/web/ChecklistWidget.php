<?php

namespace anyen\widgets\web;

use anyen\Widget;

class ChecklistWidget extends Widget
{
    private $response;

    public function render()
    {
        $this->execute();
        $html = "<ul class='checklist'>";
        foreach ($this->response as $check => $value) {
            $params = $value ? "class='success_icon'" : "class='error_icon'";
            $html .= "<li $params>$check</li>";
        }
        $html .= "</ul>";
        return $html;
    }

    public function execute()
    {
        $this->response = $this->getProperty('function')($this->wizard);
    }

    public function validate()
    {
        $this->execute();
        $passed = true;
        foreach($this->response as $response) {
            $passed &= $response;
        }
        if(!$passed && $this->getProperty('must_pass')) {
            $this->wizard->showMessage("Ensure that all items on the checklist have passed");
            return false;
        }
        return true;
    }
}
