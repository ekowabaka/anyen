<?php
namespace anyen\widgets\web;
use anyen\widgets\WebWidget;

class ChecklistWidget extends WebWidget
{
    public function render() {
        $response = $this->properties['function']($this->wizard);
        $html = "<ul class='checklist'>";
        foreach($response as $check => $value)
        {
            $params = $value ? "class='success_icon'" : "class='error_icon'";
            $html .= "<li $params>$check</li>";
        }
        $html .= "</ul>";
        return $html;
    }    
}