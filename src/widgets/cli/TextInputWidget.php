<?php
namespace anyen\widgets\cli;

class TextInputWidget extends \anyen\Widget
{
    public function render()
    {
        $response = $this->getUserResponse(
            $this->getProperty('label'),
            $this->getProperty('options'),
            $this->getProperty('default'),
            $this->getProperty('required')
        );
        return array($this->getProperty('name') => $response);
    }

    private function getUserResponse($question, $answers=null, $default=null, $required = false)
    {
        echo $question;
        if(count($answers) > 0) echo " (" . implode("/", $answers) . ")";
        echo " [$default]: ";
        $response = str_replace(array("\n", "\r"),array("",""),fgets(STDIN));

        if($response == "" && $required === true && $default == '')
        {
            echo "A value is required.\n";
            return $this->getUserResponse($question, $answers, $default, $required);
        }
        else if($response == "")
        {
            return strtolower($default);
        }
        else
        {
            if(count($answers) == 0)
            {
                return $response;
            }
            foreach($answers as $answer)
            {
                if(strtolower($answer) == strtolower($response))
                {
                    return strtolower($answer);
                }
            }
            echo "Please provide a valid answer.\n";
            return $this->getUserResponse($question, $answers, $default, $required);
        }
    }  
}