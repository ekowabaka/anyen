<?php
/*
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

use anyen\Runner;
use anyen\Wizard;


/**
 * A class for running wizards on the command line.
 */
class Cli extends Runner
{
    /**
     * Used to keep track of the last page rendered so in cases where the page
     * needs to be rendered again the titles are not rendered.
     * @var string
     */
    private $lastPageTitle = null;

    private $lastWidgetType = null;

    protected function renderPage($page, $widgets)
    {
        // Prevent the annoying repeated rendering of the title makes console clumsy
        if ($page['title'] != $this->lastPageTitle) {
            echo "\n{$page['title']}\n";
            echo str_repeat("=", strlen($page['title'])) . "\n";
        }
        $this->lastPageTitle = $page['title'];

        foreach ($widgets as $widget) {
            $response = $widget->render();
            if (is_array($response)) {
                foreach ($response as $key => $value) {
                    $this->data[$key] = $value;
                }
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

    protected function go($params)
    {
        $wizard = $this->wizardDescription;

        for ($i = 0; $i < count($wizard);) {
            $page = $wizard[$i];
            $this->runPage($page, $this->loadWidgets($page['widgets'], 'cli'));
            if(isset($page['on_next'])) {
                $page['on_next']($this->wizard);
            }
            switch ($this->wizard->getStatus()) {
                case Wizard::REPEAT:
                    break;

                case Wizard::TERMINATE:
                    $i = count($wizard);
                    break;

                default:
                    $i++;
            }

            $messages = $this->wizard->getMessages();
            foreach($messages as $message) {
                echo "{$message['message']}\n";
            }

            if ($this->lastWidgetType != 'text_input') {
                echo "\nPress ENTER to continue ...";
                $char = fgetc(STDIN);
            }
        }
    }
}

