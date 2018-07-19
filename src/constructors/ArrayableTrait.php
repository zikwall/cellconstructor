<?php

namespace zikwall\cellconstructor\constructors;

use zikwall\cellconstructor\helpers\Helper;
use zikwall\cellconstructor\exceptions\InvalidParamException;
use zikwall\cellconstructor\helpers\ArrayHelper;

trait ArrayableTrait
{
    /**
     * Основное хранилище массивов
     *
     * @var array
     */
    public $arrayTreeElementsStorange = [];

    public function unsetStorange()
    {
        unset($this->arrayTreeElementsStorange);
    }

    /**
     * Метод реализует перестановку элементов, ключем ветки массива является его же идентификатор.
     * Так же расчитывает полные "пути" и уровни вложенности
     *
     * @param $dataArray
     * @param bool $useLevels
     * @return array|InvalidParamException
     */
    public function getArrays($dataArray, $useLevels = true)
    {
        $reformedArray = [];

        if(!is_array($dataArray)){
            throw new InvalidParamException();
        }

        foreach ($dataArray as $element) {
            $reformedArray[$element['id']] = $element;
            $elementPath = $this->arrayPath($element['id'], $dataArray);
            $reformedArray[$element['id']]['path'] = $elementPath;
            if($useLevels){
                $reformedArray[$element['id']]['level'] = Helper::level($elementPath);
            }
        }
        return $reformedArray;
    }

    /**
     * @return array
     */
    public function getArrayTreeElementsStorange()
    {
        return $this->arrayTreeElementsStorange;
    }

    /**
     * Метод возвращает все последние элементы древовидного массива.
     *
     * @param $arrayTree
     * @return array
     */
    public function getArrayTreeLastLevelElements($arrayTree)
    {
        if(is_array($arrayTree)){
            foreach ($arrayTree as $items => $item){
                if(ArrayHelper::keyExists('childs', $item)){
                    $this->getArrayTreeLastLevelElements($item['childs']);
                } else {
                    $this->arrayTreeElementsStorange[] = $item;
                }
            }
        }
        return $this->arrayTreeElementsStorange;
    }

    /**
     * Данный метод строит те самые "пути" в "деревьях"
     *
     * @param $findIdentity
     * @param $array
     * @return string
     */
    public function arrayPath($findIdentity, $array = [], $keyFields = ['internal_key', 'name'])
    {
        if(!is_numeric($findIdentity)){
            throw new InvalidParamException();
        }

        $path = '';

        if(is_array($array)){
            foreach ($array as $items => $item){
                if($item['id'] == $findIdentity){
                    $path .= $item[$keyFields[0]] != 0
                        ? $this->arrayPath($item[$keyFields[0]], $array).'.'.$item[$keyFields[1]]
                        : $item[$keyFields[1]];
                }
            }
            return $path;
        }
        return '';
    }

    /**
     * Данный метод генерирует "древовидный" массив.
     * Создает новый ключ "childs" и помещает туда всех его "детей"
     *
     * @param $data
     * @return array|InvalidParamException
     */
    public function arrayTree($data, $keyFields = ['internal_key', 'sort_order', 'childs'])
    {
        $tree = [];

        if(!is_array($data)) {
            throw new InvalidParamException();
        }

        uasort($data, function($a, $b) use ($keyFields) {
            if ($a[$keyFields[1]] == $b[$keyFields[1]]) {
                return 0;
            }
            return ($a[$keyFields[1]] < $b[$keyFields[1]]) ? -1 : 1;
        });

        foreach ($data as $id => &$node) {
            if (!$node[$keyFields[0]]){
                $tree[$id] = &$node;
            } else {
                $data[$node[$keyFields[0]]][$keyFields[2]][$id] = &$node;
            }
        }
        return $tree;
    }
}