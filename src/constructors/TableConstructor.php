<?php

namespace zikwall\cellconstructor\constructors;

use Donquixote\Cellbrush\Table\Table;
use zikwall\cellconstructor\constructors\interfaces\ConstructorInterface;
use zikwall\cellconstructor\containers\ContentContainer;
use zikwall\cellconstructor\helpers\EditableHelper;
use zikwall\cellconstructor\helpers\Helper;
use zikwall\cellconstructor\exceptions\InvalidParamException;

class TableConstructor implements ConstructorInterface
{
    use ArrayableTrait;

    /**
     * @var ContentConstructor
     */
    private $contentConstructor;
    /**
     * @var array
     */
    public $upHierarchy = [];
    /**
     * @var array
     */
    public $leftHierarchy = [];

    /**
     * TableConstructor constructor.
     */
    public function __construct()
    {
        $this->contentConstructor = new ContentConstructor();
    }

    public function getContentConstructor() : ContentConstructor
    {
        return $this->contentConstructor;
    }

    public function getContentContainer() : ContentContainer
    {
        return $this->getContentConstructor()->getContainer();
    }

    public function drawEditableCells(array $upTree, array $leftTree, string $container = null, bool $isEditable = true, bool $isUseContainer = true) : array
    {
        $matrixElementsStorange = [];
        $counterAxis = 0;

        foreach ($this->getArrayTreeLastLevelElements($upTree) as $ordinatesAxis => $yAxisElement){
            foreach($this->getArrayTreeLastLevelElements($leftTree) as $abscissaeAxis => $xAxisElement){
                if($xAxisElement['isNullRow'] == 1 || $xAxisElement['heading'] == 1){
                    $matrixElementsStorange[$xAxisElement['id'].$yAxisElement['id']][0] = trim($ordinatesAxis);
                    $matrixElementsStorange[$xAxisElement['id'].$yAxisElement['id']][1] = trim($abscissaeAxis);
                    $matrixElementsStorange[$xAxisElement['id'].$yAxisElement['id']]['CoulumnIdentity'] = trim($yAxisElement['id']);
                    $matrixElementsStorange[$xAxisElement['id'].$yAxisElement['id']]['RowIdentity'] = trim($xAxisElement['id']);
                    $matrixElementsStorange[$xAxisElement['id'].$yAxisElement['id']]['hpath'] = trim($yAxisElement['path']);
                    $matrixElementsStorange[$xAxisElement['id'].$yAxisElement['id']]['vpath'] = trim($xAxisElement['path']);
                    if($isEditable){
                        if($isUseContainer){
                            $check = $this->getContentContainer()->checkMatrixRecord($container, trim($yAxisElement['path']), trim($xAxisElement['path']), null, null, false);
                            $value = $check ? $check : 'cell'.$yAxisElement['id'].'-'.$xAxisElement['id'];
                        } else {
                            $value = 'cell'.$yAxisElement['id'].'-'.$xAxisElement['id'];
                        }

                        $matrixElementsStorange[$xAxisElement['id'].$yAxisElement['id']]['value'] = $this->drawEditableCell($yAxisElement['field_type'], 'cell'.$yAxisElement['id'].'-'.$xAxisElement['id'],
                            $value,
                            [
                                'colId'   => trim($yAxisElement['id']),
                                'rowId'   => trim($xAxisElement['id']),
                                'colPath' => trim($yAxisElement['path']),
                                'rowPath' => trim($xAxisElement['path'])
                            ], $container);
                    }
                }
                $counterAxis++;
            }
        }
        return $matrixElementsStorange;
    }

    public function drawEditableCell($discoverType, $fullIdentity, $value, $dataParams = [], $container = null) : string
    {
        $type = EditableHelper::determineFieldType($discoverType);
        return $field = EditableHelper::createEditableField($fullIdentity, $type, $value, $container, $dataParams);
    }

    public function drawMatrixTable(array $upHierarchy, array $leftHierarchy, string $container, bool $editTable = false, bool $countainerUse = true) : Table
    {
        $openEndCols = [];

        if($upHierarchy == null || !is_array($upHierarchy)){
            throw new InvalidParamException('Вверхняя иерархия пуста или не массив!');
        }

        if($leftHierarchy == null || !is_array($leftHierarchy)){
            throw new InvalidParamException('Боковая иерархия пуста или не массив!');
        }

        $colNames = $this->initUpHierarchy($upHierarchy);
        $rowHierarchy = $this->initLeftHierarchy($leftHierarchy);

        $table = new Table();

        $rowGroupName = 'g';
        $rowGroupNames = [$rowGroupName];

        for ($i = 0; $i < EditableHelper::depth($colNames); ++$i) {
            $table->thead()->addRow($rowGroupName . '.caption');
            $rowGroupName .= '.g';
            $rowGroupNames[] = $rowGroupName;
        }
        $table->thead()->addRow($rowGroupName);

        for ($i = 0; $i <= EditableHelper::depth($rowHierarchy); $i++) {
            $openEndCols[] = $i;
        }

        $table->addColNames($openEndCols);
        $table->thead()->thOpenEnd('g', 0, '');

        foreach ($rowHierarchy as $row => $isLeaf){
            $table->addRow($row);
            $table->thOpenEnd($row, $isLeaf['level'], $isLeaf['name']);
            if($isLeaf['isNullRow'] == 0 || $isLeaf['heading'] == 0){
                $table->tbody()->addCellClass($row, $isLeaf['level'], 'center');
            }
        }

        foreach ($colNames as $colName => $isLeaf) {
            $table->addColName($colName);
            $depth = substr_count($colName, '.');
            $rowGroupName = $rowGroupNames[$depth];
            $rowName = $isLeaf['leaf']
                ? $rowGroupName
                : $rowGroupName . '.caption';
            $table->thead()->th($rowName, $colName, $isLeaf['name']);
        }


        if($editTable){
            $contentContainermMatrixStorange = $this->drawEditableCells($upHierarchy, $leftHierarchy, $container, $countainerUse);

            foreach ($contentContainermMatrixStorange as $data){
                $table->tbody()->td($data['vpath'], $data['hpath'], $data['value']);
            }

        } else {
            $contentContainermMatrixStorange = $this->getContentContainer()->getMatrixContentContainer()->findContainer($container);

            if(empty($contentContainermMatrixStorange)){
                throw new InvalidParamException('Таблица с данным '.$container.' является пустой!');
            }

            foreach ($contentContainermMatrixStorange as $data){
                $table->tbody()->td($data->row, $data->column, $data->value);
            }
        }

        $table->addClass('table table-bordered table-striped');

        return $table;
    }

    public function drawFlatTable(array $upHierarchy, array $determination, string $tableName) : Table
    {
        $hierarchy = $this->initUpHierarchy($upHierarchy);

        if($hierarchy == null || !is_array($hierarchy)){
            throw new InvalidParamException();
        }

        $colNames = $hierarchy;
        $determinationItems = $determination;

        $table = new Table();
        $rowGroupName = 'g';
        $rowGroupNames = [$rowGroupName];

        for ($i = 0; $i < EditableHelper::depth($colNames); ++$i) {
            $table->thead()->addRow($rowGroupName . '.caption');
            $rowGroupName .= '.g';
            $rowGroupNames[] = $rowGroupName;
        }
        $table->thead()->addRow($rowGroupName);

        foreach ($colNames as $colName => $isLeaf) {
            $table->addColName($colName);
            $depth = substr_count($colName, '.');
            $rowGroupName = $rowGroupNames[$depth];
            $rowName = $isLeaf['leaf']
                ? $rowGroupName
                : $rowGroupName . '.caption';
            $table->thead()->th($rowName, $colName, $isLeaf['name']);
        }

        $contentContainerDataset = $this->getContentContainer()->getTabularContentContainer()->findContainer($tableName);

        if(!is_array($contentContainerDataset)){
            throw new InvalidParamException('Таблица с данным '.$tableName.' является пустой!');
        }

        $rowCounter = 1;
        foreach ($contentContainerDataset as $content){
            $table->addRow($rowCounter);
            foreach ($determinationItems as $item => $value){
                $table->tbody()->td($rowCounter, $value['path'], $content->$value['column']);
            }
            $rowCounter++;
        }

        $table->addClass('table table-bordered');

        return $table;
    }

    /**
     * Данный метод генерирует "строковую" таблицу
     *
     * @param array $rows массив с определениме иерархии боковой шапки
     * @return Table
     */
    public function drawRowsTable(array $rows) : Table
    {
        $openEndCols = [];

        if(!is_array($rows)){
            throw new InvalidParamException('Очень плахой массив!!!');
        }

        $rowHierarchy = $this->initLeftHierarchy($rows);
        $table = new Table();

        for ($i = 0; $i <= EditableHelper::depth($rowHierarchy); $i++) {
            $openEndCols[] = $i;
        }

        $table->addColNames($openEndCols);
        $table->thead()->thOpenEnd('g', 0, '');

        foreach ($rowHierarchy as $row => $isLeaf){
            $table->addRow($row);
            $table->thOpenEnd($row, $isLeaf['level'], $isLeaf['name']);
            if($isLeaf['isNullRow'] == 0 || $isLeaf['heading'] == 0){
                $table->tbody()->addCellClass($row, $isLeaf['level'], 'center');
            }
        }
        $table->addClass('table table-bordered');
        return $table;
    }

    public function initUpHierarchy(array $upContent) : array
    {
        foreach ($upContent as $els => $el) {
            $this->upHierarchy[trim($el['path'])] = is_array($el['childs'])
                ? ['leaf' => false, 'name' => $el['field_name'], 'identity' => $el['id'], 'level' => Helper::level($el['path'])]
                : ['leaf' => true, 'name' => $el['field_name'], 'identity' => $el['id'], 'level' => Helper::level($el['path'])];
            if (isset($el['childs'])) {
                $this->initUpHierarchy($el['childs']);
            }
        }
        return $this->upHierarchy;
    }

    public function initLeftHierarchy(array $leftContent, $isConstruct = false) : array
    {
        foreach ($leftContent as $els => $el) {
            $this->leftHierarchy[trim($el['path'])] = is_array($el['childs'])
                ? ['leaf' => false, 'name' => trim($el['field_name']), 'identity' => $el['id'], 'level' => Helper::level($el['path']), 'isNullRow' => $el['isNullRow'], 'header' => $el['heading']]
                : ['leaf' => true, 'name' => trim($el['field_name']), 'identity' => $el['id'], 'level' => Helper::level($el['path']), 'isNullRow' => $el['isNullRow'], 'header' => $el['heading']];
            if (isset($el['childs'])) {
                $this->initLeftHierarchy($el['childs']);
            }
        }
        return $this->leftHierarchy;
    }
}