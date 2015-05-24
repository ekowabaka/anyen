<?php
namespace anyen\wizard;

function page()
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

function text($text)
{
    return [
        'type' => 'text',
        'text' => $text
    ];
}

function checklist($callable)
{
    return [
        'type' => 'checklist',
        'function' => $callable
    ];
}

function input($label, $name, $options = array())
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

function onroute($function)
{
    return [
        'onroute' => $function
    ];
}

function onrender($function)
{
    return [
        'onrender' => $function
    ];
}

function call($function)
{
    return [
        'type' => 'function',
        'function' => $function
    ];
}
