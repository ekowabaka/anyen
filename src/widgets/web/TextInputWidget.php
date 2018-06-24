<?php

namespace anyen\widgets\web;

use anyen\Widget;

class TextInputWidget extends Widget
{
    private $message;

    public function render()
    {
        $dataValue = $this->wizard->getValue($this->getProperty('name'));
        $value = $dataValue == '' ? $this->getProperty('default') : $dataValue;
        $type = $this->getProperty('masked') == 'true' ? 'password' : 'text';

        if (count($this->getProperty('options') ?? []) > 0) {
            $html = "<label>{$this->getProperty('label')}</label> " .
                "<span class='error-message' id='{$this->getProperty('name')}_message'>$this->message</span>" .
                "<select name='{$this->getProperty('name')}' type='$type' value='$value'>";
            $valueSource = $this->getProperty('keys_as_values');
            foreach ($this->getProperty('options') as $key => $option) {
                $value = $valueSource ? $key : $option;
                $html .= "<option value='{$value}'>{$option}</option>";
            }
            $html .= "</select>";

            return $html;
        } else {
            return "<label>{$this->getProperty('label')}</label>
                <span class='error-message' id='{$this->getProperty('name')}_message'>$this->message</span>
                <input name='{$this->getProperty('name')}' type='$type' value='$value' />";
        }
    }

    public function validate()
    {
        $value = trim($this->wizard->getValue($this->getProperty('name')));
        if($this->getProperty('required') && !$value) {
            $this->message = "This field is required";
            return false;
        }
        return true;
    }
}
