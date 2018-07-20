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
     *      Format:
     *      [1] => Array(
     *          [id] => 1
     *          [name] => F1
     *          [field_name] => Mazda
     *          [internal_key] => 0
     *          [sort_order] => 1
     *      )
     * @param bool $useLevels flag подсчитывать уровни или нет
     *
     * @return array
     *      Format:
     *      [1] => Array(
     *          [id] => 1
     *          [name] => F1
     *          [field_name] => Mazda
     *          [internal_key] => 0
     *          [sort_order] => 1
     *          [orientation] => 1
     *          [lvl] => 0
     *          [path] => F1
     *      )
     */
    public function getArrays(array $dataArray, bool $useLevels = true) : array
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

    public function getArrayTreeElementsStorange() : array
    {
        return $this->arrayTreeElementsStorange;
    }

    /**
     * Метод возвращает все последние элементы древовидного массива.
     * @return array
     *      Format:
     *      [1] => Array(
     *          [id] => 1
     *          [name] => F1
     *          [field_name] => Mazda
     *          [internal_key] => 0
     *          [sort_order] => 1
     *          [lvl] => 0
     *          [path] => F1
     *      )
     *
     */
    public function getArrayTreeLastLevelElements(array $arrayTree) : array
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
     * @return string
     *      Format: [path] => F3.F10.F7
     */
    public function arrayPath(int $findIdentity, array $array = [], $keyFields = ['internal_key', 'name']) : string
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
     *
     * @return array
     *          Format:
     *          [1] => Array(
     *              [id] => 1
     *              [name] => F1
     *              [field_name] => Mazda
     *              [internal_key] => 0
     *              [sort_order] => 1
     *              [lvl] => 0
     *              [path] => F1
     *              [childs] => Array (
     *                      [6] => Array (
     *                          [id] => 6
     *                          [name] => F6
     *                          [field_name] => Mazda CX-9
     *                          [internal_key] => 1
     *                          [sort_order] => 1
     *                          [lvl] => 0
     *                          [path] => F1.F6
     *                      )...
     */
    public function arrayTree(array $data, $keyFields = ['internal_key', 'sort_order', 'childs']) : array
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