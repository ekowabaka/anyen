<?php
namespace anyen\widgets\web;
use anyen\widgets\WebWidget;

class TextInputWidget extends WebWidget
{    
    public function render() 
    {   
        $dataValue = $this->wizard->getData($this->properties['name']);
        $value = $dataValue == '' ? $this->properties['default'] : $dataValue;
        $type = $this->properties['masked'] == 'true' ? 'password' : 'text';
        
        if(count($this->properties['options']) > 0)
        {
            $html = "<label>{$this->properties['label']}</label> " . 
                "<span class='error-message' id='{$this->properties['name']}_message'></span>" . 
                "<select name='{$this->properties['name']}' type='$type' value='$value'>";
            foreach($this->properties['options'] as $option)
            {
                $html .= "<option value='{$option}'>{$option}</option>";
            }
            $html .= "</select>";
                
            return $html;
        }
        else
        {
            return "<label>{$this->properties['label']}</label>
                <span class='error-message' id='{$this->properties['name']}_message'></span>
                <input name='{$this->properties['name']}' type='$type' value='$value' />";
        }
    }
}
