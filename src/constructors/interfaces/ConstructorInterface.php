<?php
namespace zikwall\cellconstructor\constructors\interfaces;

use Donquixote\Cellbrush\Table\Table;

interface ConstructorInterface
{
    /**
     * Метод генерирует матричную таблицу, так же опционально Editable поля
     *
     * @param $upTree
     * @param $leftTree
     * @param bool $isEditable
     * @return array
     */
    public function drawEditableCells(array $upTree, array $leftTree, string $container = null, bool $isEditable = true, bool $isUseContainer = true) : array;

    /**
     * Создает Editable поле для матричной таблицы на основе типа поля
     *
     * @param $column
     * @param $fullIdentity
     * @param string $fieldType
     * @return string
     */
    public function drawEditableCell($discoverType, $fullIdentity, $value, $dataParams = [], $container = null) : string;

    /**
     * Основной метод генерации матричной таблицы
     *
     * @param [] $upHierarchy массив с определениме иерархии шапки таблицы
     *      Format: @see ArryableTrait::arrayTree() result array
     * @param [] $leftHierarchy  массив с опеределнием иерархии боковой шапки
     *      Format: @see ArryableTrait::arrayTree() result array
     *
     * @param string $container контейнер данных, передается имя таблицы
     * ```php
     * $contentContainermMatrixStorange = $this->getContentContainer()
     *      ->getMatrixContentContainer()
     *      ->findContainer($container);
     * ```
     * @param bool $editTable [] флажок переключения между методами отображения таблицы
     * состояния:
     *  1. true - отображать editable mode
     *  2. false - отображать таблицу с датасетом
     *
     * @return Table
     */
    public function drawMatrixTable(array $upHierarchy, array $leftHierarchy, string $container, bool $editTable = false, bool $countainerUse = true) : Table;

    /**
     * Метод генерирует плоскую таблицу, в качестве хранилища данных выступает физическая таблица
     *
     * @param array $upHierarchy  массив с определениме иерархии шапки таблицы
     *      Format: @see ArryalleTrait::arrayTree() result array
     * @param array $determination массив с определениме связи физической таблицы и столбцов сгенерированной матрицы
     *      Format: @see ContentConstructor::createDetermination() result array
     * @param string $tableName имя таблицы, для определения набора данных
     * @return Table
     */
    public function drawFlatTable(array $upHierarchy, array $determination, string $tableName) : Table;

    public function drawRowsTable(array $rows) : Table;

    /**
     * @param $upContent
     *        Format: @see ArryalleTrait::arrayTree() result array
     * @return array
     *      Format:
     *          [F1] => Array(
     *              [leaf] =>
     *              [name] => Mazda
     *              [identity] => 1
     *              [level] => 0
     *          )
     *          [F1.F6] => Array(
     *              [leaf] => 1
     *              [name] => Mazda CX-9
     *              [identity] => 6
     *              [level] => 1
     *          )
     *          [F1.F4] => Array(
     *              [leaf] => 1
     *              [name] => Mazda6
     *              [identity] => 4
     *              [level] => 1
     *          )...
     */
    public function initUpHierarchy(array $upContent) : array;

    /**
     * @param $leftContent
     *      Format: @see ArryalleTrait::arrayTree() result array
     * @param bool $isConstruct
     * @return array
     *      Format:
     *          [F16] => Array(
     *              [leaf] =>
     *              [name] => Characteristics
     *              [identity] => 16
     *              [level] => 0
     *              [isNullRow] =>
     *              [header] => 1
     *          )
     *          [F16.F18] => Array(
     *              [leaf] =>
     *              [name] => Motor
     *              [identity] => 17
     *              [level] => 1
     *              [isNullRow] =>
     *              [header] => 1
     *          )
     *          [F16.F18.F21] => Array(
     *              [leaf] => 1
     *              [name] => HorsePower
     *              [identity] => 20
     *              [level] => 2
     *              [isNullRow] =>
     *              [header] => 1
     *          )...
     */
    public function initLeftHierarchy(array $leftContent, $isConstruct = false) : array;

}