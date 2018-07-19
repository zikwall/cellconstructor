<?php

namespace zikwall\cellconstructor;

class ConstructorComponent
{
    /**
     * @var ConstructorConfiguration
     */
    public static $component;

    /**
     * @return string
     */
    public static function getExamplesDir()
    {
        return dirname(__DIR__) . '/examples';
    }
}