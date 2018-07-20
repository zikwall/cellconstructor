<?php
namespace zikwall\cellconstructor\constructors\interfaces;

interface ContentConstructorInterface
{
    public function commandCreateMatrixStorange(array $y = [], array $x = [], string $tableName, bool $isInsert = true, bool $isReturnedInsertIds = false);

    public function commandSaveMatrixTemplate($templateName, $upHierarchy = [], $leftHierarchy = [], $isReturnedInsertId = false, $type = 2, $charset = 'utf8', $engine = 'InnoDb');

    public function commandSaveFlatTemplate($templateName, $hierarchy, $isReturnedInsertId = false, $type = 1, $charset = 'utf8', $engine = 'InnoDb');

    public function commandSaveReport($tableName, $reportName, $templateIdentity, $isReturnedInsertId = false);

    public function createDetermination(array $hierarchy = []);
}