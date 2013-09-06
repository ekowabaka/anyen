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
                {$this->properties['name']}messageBox.style.display = 'block';
                var classAttr = document.createAttribute('class');
                classAttr.nodeValue = 'error';
                document.getElementById('{$this->properties['name']}').setAttributeNode(classAttr);
                validated = false;
            }";
        }
        
        $getData = "data += '{$this->properties['name']}=' + escape(document.getElementById('{$this->properties['name']}').value) + '&';";
        $value = $this->properties['default'];
        return array(
            'html' => "<label>{$this->properties['label']}</label>
                <span class='error-message' id='{$this->properties['name']}_message'></span>
                <input id='{$this->properties['name']}' type='text' value='$value'>",
            'validation' => $validation,
            'get_data' => $getData
        );
    }
}
