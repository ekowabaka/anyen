<?php
/**
 * Copyright (c) 2012 James Ekow Abaka Ainooson
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 * 
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * 
 */

namespace anyen\runners;

/**
 * A class for running wizards on the command line.
 */
class Cli extends \anyen\Runner
{
    /**
     * Used to keep track of the last page rendered so in cases where the page
     * needs to be rendered again the titles are not rendered.
     * @var string
     */
    private $lastPage = null;
    
    protected function renderPage($page) 
    {
        // Prevent the annoying repeated rendering of the title makes console clumsy
        if($page['page'] != $this->lastPage)
        {
            echo "\n{$page['title']}\n";
            echo str_repeat("=", strlen($page['title'])) . "\n";
        }
        $this->lastPage = $page['page'];
        
        if(is_array($page['widgets']))
        {
            foreach($page['widgets'] as $widget)
            {
                $this->renderWidget($widget);
            }
        }
    }
    
    public function showMessage($message)
    {
        echo "$message\n";
    }
    
    public function out($string)
    {
        echo "$string\n";
    }
    
    /**
     * Renders the CLI version of the current widget needed by the wizard.
     * 
     * @param type $widget 
     */
    private function renderWidget($widget)
    {
        /*$widgetClass = self::getClassName("cli_{$widget['type']}_widget");
        $widgetObject = new $widgetClass($widget);
        $widgetObject->setWizard($this);*/
        
        $response = $this->loadWidget($widget, 'cli')->run();
        if(is_array($response))
        {
            foreach($response as $key => $value)
            {
                $this->data[$key] = $value;
            }
        }
    }
    
    protected function go($params)
    {
        $wizard = $this->wizardDescription;
        $this->setCallbackObject($params['callback_object']);
        
        for($i = 0; $i < count($wizard); $i++)
        {
            $page = $wizard[$i];
            
            $this->resetStatus();
            $this->executeCallback("{$page['page']}_render_callback");
            
            switch($this->getStatus())
            {
                case Anyen::STATUS_REPEAT:
                    $i--;
                    continue;
                    
                case Anyen::STATUS_TERMINATE:
                    $i = count($wizard);
                    continue;
            }
            
            $this->runPage($page);
            $this->executeCallback("{$page['page']}_route_callback");
            
            switch($this->getStatus())
            {
                case Anyen::STATUS_REPEAT:
                    $i--;
                    break;
                
                case Anyen::STATUS_TERMINATE:
                    $i = count($wizard);
                    break;
            }
            
            echo "\nPress ENTER to continue ...";
            fgets(STDIN);
            echo "\n";
        }        
    }
}
