<?php

namespace zikwall\cellconstructor\helpers;

use zikwall\cellconstructor\exceptions\InvalidParamException;

class Helper
{
    /**
     * @param $array
     */
    public static function p($array){
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }

    /**
     * Определение максимальной глубины массива, по ключу в виде путей.
     *
     * Пример:
     * ```php
     * [AA] - 0
     * [AA.BB.CC] - 2
     * ```
     *
     * @param $array
     * @param string $needle
     * @return int|mixed
     */
    public static function depth($array, $needle = '.')
    {
        $maxdepth = 0;
        foreach ($array as $key => $value) {
            $depth = substr_count($key, $needle);
            $maxdepth = max($maxdepth, $depth);
        }

        return $maxdepth;
    }

    /**
     * Определение текущего уровня элемента массива.
     *
     * @param $string
     * @param string $needle
     * @return int
     */
    public static function level($string, $needle = '.')
    {
        return substr_count($string, $needle);
    }

    /**
     * @param $array
     * @return InvalidParamException
     */
    public static function isEmptyArray($array)
    {
        if($array != null){
            if(!is_array($array)){
                throw new InvalidParamException();
            }
        }
        throw new InvalidParamException();
    }

    /**
     * Преобразование массива ПОСТ в сгурппированный массив.
     *
     * @param $post
     * @return array
     */
    public static function truePostArray($post)
    {
        $truePostArray = [];

        foreach($post as $k => $v) {
            foreach($v as $key => $val) {
                $truePostArray[$key][$k] = $val;
            }
        }

        return $truePostArray;
    }

    /**
     * @param $apostrophedValue
     * @return string
     */
    public static function apostrophe($apostrophedValue)
    {
        return '`'.$apostrophedValue.'`';
    }

    /**
     * @param $bracketsValue
     * @return string
     */
    public static function brackets($bracketsValue)
    {
        return '(' . $bracketsValue . ')';
    }
}