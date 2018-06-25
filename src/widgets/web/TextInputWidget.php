<?php

namespace anyen\widgets\web;

use anyen\Widget;

class TextInputWidget extends Widget
{
    public function render()
    {
        $dataValue = $this->wizard->getValue($this->getProperty('name'));
        $value = $dataValue == '' ? $this->getProperty('default') : $dataValue;
        $type = $this->getProperty('masked') == 'true' ? 'password' : 'text';
        $message = $_SESSION["{$this->getProperty('name')}_message"] ?? "";
        unset($_SESSION["{$this->getProperty('name')}_message"]);

        if (count($this->getProperty('options') ?? []) > 0) {
            $html = "<label>{$this->getProperty('label')}</label> " .
                "<span class='error-message' id='{$this->getProperty('name')}_message'>$message</span>" .
                "<select name='{$this->getProperty('name')}' type='$type' value='$value'>";
            $valueSource = $this->getProperty('keys_as_values');
            foreach ($this->getProperty('options') as $key => $option) {
                $value = $valueSource ? $key : $option;
                $selected = $dataValue == $value ? "selected='selected'" : "";
                $html .= "<option value='{$value}' $selected>{$option}</option>";
            }
            $html .= "</select>";
            return $html;
        } else {
            return "<label>{$this->getProperty('label')}</label>
                <span class='error-message' id='{$this->getProperty('name')}_message'>$message</span>
                <input name='{$this->getProperty('name')}' type='$type' value='$value' />";
        }
    }

    public function validate()
    {
        $value = trim($this->wizard->getValue($this->getProperty('name')));
        if($this->getProperty('required') && !$value) {
            $_SESSION["{$this->getProperty('name')}_message"] = "This field is required";
            return false;
        }
        return true;
    }
}
