<?php

namespace anyen\widgets\web;

use anyen\Widget;

class ChecklistWidget extends Widget
{
    public function render()
    {
        $function = $this->getProperty('function');
        $response = $function($this->wizard);
        $html = "<ul class='checklist'>";
        foreach ($response as $check => $value) {
            $params = $value ? "class='success_icon'" : "class='error_icon'";
            $html .= "<li $params>$check</li>";
        }
        $html .= "</ul>";
        return $html;
    }
}
