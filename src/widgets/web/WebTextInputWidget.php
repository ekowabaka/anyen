<?php

class WebTextInputWidget extends WebWidget
{    
    public function render() 
    {
        if($this->properties['required'])
        {
            $validation = "if(document.getElementById('{$this->properties['name']}').value == '') {
                var {$this->properties['name']}messageBox = document.getElementById('{$this->properties['name']}_message');
                {$this->properties['name']}messageBox.innerHTML = 'This field is required';
                var classAttr = document.createAttribute('class');
                classAttr.nodeValue = 'error';
                document.getElementById('{$this->properties['name']}').setAttributeNode(classAttr);
                validated = false;
            }";
        }
        
        $getData = "data += '{$this->properties['name']}=' + escape(document.getElementById('{$this->properties['name']}').value) + '&';";
        
        return array(
            'html' => "<label>{$this->properties['label']}<label><br/>
                <span id='{$this->properties['name']}_message'></span></br>
                <input id='{$this->properties['name']}' type='text' value='{$_GET[$this->properties['name']]}'>",
            'validation' => $validation,
            'get_data' => $getData
        );
    }
}
