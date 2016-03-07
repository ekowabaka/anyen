<?php
namespace anyen;

/**
 * Description of Wizard
 *
 * @author ekow
 */
class Wizard
{   
    public static function banner($text)
    {
        return ['banner' => $text];
    }

    public static function page()
    {
        $args = func_get_args();
        $title = array_shift($args);
        $widgets = [];
        $options = [
            'onrender' => function(){},
            'onroute' => function(){}
        ];

        foreach($args as $arg)
        {
            if(isset($arg['type']))
            {
                $widgets[] = $arg;
            }
            else
            {
                $options = array_merge($options, $arg);
            }
        }

        return array_merge(
            [
                'title' => $title,
                'widgets' => $widgets
            ], 
            $options
        );
    }

    public static function text($text)
    {
        return [
            'type' => 'text',
            'text' => $text
        ];
    }

    public static function checklist($callable)
    {
        return [
            'type' => 'checklist',
            'function' => $callable
        ];
    }

    public static function input($label, $name, $options = array())
    {
        return array_merge(
            [
                'type' => 'text_input',
                'label' => $label,
                'name' => $name
            ], 
            $options
        );
    }

    public static function onroute($function)
    {
        return [
            'onroute' => $function
        ];
    }

    public static function onrender($function)
    {
        return [
            'onrender' => $function
        ];
    }

    public static function call($function)
    {
        return [
            'type' => 'function',
            'function' => $function
        ];
    }
    
    public static function finish($url = '')
    {
        return ['finish' => true];
    }
}
