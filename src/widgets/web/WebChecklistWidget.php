<?php
class WebChecklistWidget extends WebWidget
{
    public function render() {
        $response = $this->wizard->executeCallback($this->properties['function_name'], array());
        $html = "<ul class='checklist'>";
        foreach($response as $check => $value)
        {
            $params = $value ? "class='checked'" : "class='unchecked'";
            $html .= "<li $params>$check</li>";
        }
        $html .= "</ul>";
        return array(
            'html' => $html
        );
    }    
}