<?php

namespace zikwall\cellconstructor\constructors;

use Donquixote\Cellbrush\Table\Table;
use zikwall\cellconstructor\helpers\EditableHelper;
use zikwall\cellconstructor\exceptions\InvalidParamException;

/**
 * Данный класс является "симмулятором" генератора таблиц.
 * Создает все виды отчетов с "искуственными" данными.
 * Нужен при создании шаблонов.
 *
 * Class SimmulateTableConstructor
 */
class SimmulateTableConstructor extends TableConstructor
{
    /**
     * @param $upHierarchy
     *      Format: @see ArryalleTrait::arrayTree() result array
     * @param bool $editTable flag
     * @return Table
     */
    public function simmulateFlatTable(array $upHierarchy, bool $editTable = false)
    {
        if($upHierarchy == null || !is_array($upHierarchy)){
            throw new InvalidParamException();
        }

        $colNames = $this->initUpHierarchy($upHierarchy);

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

        $table->tbody()->addRow('contentRow');
        //$lastElementCounts = count($this->getArrayTreeLastLevelElements($upHierarchy));

        //for($i = 0; $i <= $lastElementCounts; $i++){
        //foreach ($colNames as $colName => $isLeaf){
        /**
         * toDo: create recursive generated fill downs row
         */
        //$table->tbody()->td('contentRow', trim($colName), '{{content here}}');
        //}
        //}
        $table->addClass('table table-bordered');
        return $table;
    }

    /**
     * @param $upHierarchy
     *      Format: @see ArryalleTrait::arrayTree() result array
     *
     * @param $leftHierarchy
     *      Format: @see ArryalleTrait::arrayTree() result array
     *
     * @param bool $editTable flag on\off editable mod, this method is a dummy
     * @return Table
     */
    public function simmulateMatrxiTable(array $upHierarchy, array $leftHierarchy, $editTable = true) : Table
    {
        $openEndCols = [];

        if($upHierarchy == null || !is_array($upHierarchy)){
            throw new InvalidParamException();
        }

        if($leftHierarchy == null || !is_array($leftHierarchy)){
            throw new InvalidParamException();
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

        for ($rowOpenEndCounter = 0; $rowOpenEndCounter <= EditableHelper::depth($rowHierarchy); $rowOpenEndCounter++) {
            $openEndCols[] = $rowOpenEndCounter;
        }

        $table->addColNames($openEndCols);
        $table->thead()->thOpenEnd('g', 0, '');

        foreach ($rowHierarchy as $row => $isLeaf){
            $table->addRow($row);
            $table->thOpenEnd($row, $isLeaf['level'], $isLeaf['name']);
            if($isLeaf['isNullRow'] == 0 || $isLeaf['heading'] == 1){
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

        $matrix = $this->drawEditableCells($upHierarchy, $leftHierarchy);

        if($editTable){
            foreach ($matrix as $data){
                $table->tbody()->td($data['xAxisElementPath'], $data['yAxisElementPath'], $data['crossValue']);
            }
        }

        $table->addClass('table table-bordered table-striped');
        return $table;
    }

    /**
     * @param $upTree
     *      Format: @see ArryalleTrait::arrayTree() result array
     * @param $leftTree
     *      Format: @see ArryalleTrait::arrayTree() result array
     * @param null $container
     * @param bool $isEditable $editTable flag on\off editable mod, this method is a dummy
     * @return array
     */
    public function drawEditableCells(array $upTree, array $leftTree, string $container = null, bool $isEditable = true, bool $isUseContainer = true) : array
    {
        $matrixElementsStorange = [];
        $counterAxis = 0;

        foreach ($this->getArrayTreeLastLevelElements($upTree) as $ordinatesAxis => $yAxisElement){
            foreach($this->getArrayTreeLastLevelElements($leftTree) as $abscissaeAxis => $xAxisElement){
                if($xAxisElement['isNullRow'] == 1 || $xAxisElement['heading'] == 1){
                    $matrixElementsStorange[$counterAxis]['ordinatesAxisIdentity'] = trim($ordinatesAxis);
                    $matrixElementsStorange[$counterAxis]['abscissaeAxisIdentity'] = trim($abscissaeAxis);
                    $matrixElementsStorange[$counterAxis]['coulumnIdentity'] = trim($yAxisElement['id']);
                    $matrixElementsStorange[$counterAxis]['rowIdentity'] = trim($xAxisElement['id']);
                    $matrixElementsStorange[$counterAxis]['yAxisElementPath'] = trim($yAxisElement['path']);
                    $matrixElementsStorange[$counterAxis]['xAxisElementPath'] = trim($xAxisElement['path']);
                    $matrixElementsStorange[$counterAxis]['crossValue'] = 'cell_'.$xAxisElement['id'].'-'.$yAxisElement['id'].'_content';
                }
                $counterAxis++;
            }
        }
        return $matrixElementsStorange;
    }
}