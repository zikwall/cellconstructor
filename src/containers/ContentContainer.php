<?php

namespace zikwall\cellconstructor\containers;

use zikwall\cellconstructor\containers\interfaces\ContentContainerInterface;
use zikwall\cellconstructor\helpers\Helper;
use zikwall\cellconstructor\exceptions\InvalidParamException;
use zikwall\cellconstructor\ConstructorComponent as CC;
class ContentContainer implements ContentContainerInterface
{
    /**
     * @var \Pixie\QueryBuilder\QueryBuilderHandler
     */
    public $container;

    public function __construct()
    {
        $this->container = CC::$component->getDb();
    }

    public function getMatrixContentContainer() : MatrixContentContainer
    {
        return new MatrixContentContainer();
    }

    public function getTabularContentContainer() : FlatContentContainer
    {
        return new FlatContentContainer();
    }

    /**
     * Выборка отчетов для конкретного шаблона
     *
     * @param int $templateId идентификатор шаблона
     * @return bool|null|\stdClass
     */
    public function findTemplateReports(int $templateId)
    {
        $query = $this->container->table('reports')
            ->select('reports.*')
            ->where('template_id', '=', $templateId);

        if($query->count() > 0){
            return $query->get();
        }

        return false;
    }

    /**
     * Выборка шаблона
     *
     * @param int $templateId идентификатор шаблона
     * @return bool|null|\stdClass
     */
    public function findTemplate(int $templateId, int $type = null)
    {
        $query = $this->container->table('templates')
            ->select('templates.*')
            ->where('id', '=', $templateId);

        if($type != null){
            $query->where('type', '=', $type);
        }

        if($query->count() > 0){
            return $query->first();
        }

        return false;
    }

    /**
     * Выборка шаблонов
     *
     * @return bool|null|\stdClass
     */
    public function findTemplates(int $type = null)
    {
        $query = $this->container->table('templates')->select('templates.*');

        if(!empty($type)){
            $query->where('type', '=', $type);
        }

        if($query->count() > 0){
            return $query->get();
        }

        return false;
    }

    /**
     * Выборка конкретного отчета
     *
     * @param $reportId int дентификатор отчета
     * @param bool $isJoinTemplate флаг - присоеденинять таблицу шаблонов или нет
     * @return bool|null|\stdClass
     */
    public function findReport($reportId, $isJoinTemplate = true)
    {
        $query = $this->container->table($this->container->raw('reports as r'))
            ->select('r.*')->where('r.id', '=', $reportId);

        if($isJoinTemplate){
            $query->select($this->container->raw('templates.id as tmpId, templates.name, templates.user_id as tmp_userId'))
                  ->select(['templates.hierarchy', 'templates.leftHierarchy', 'templates.determination', 'templates.type'])
                  ->leftJoin('templates', 'templates.id', '=', 'r.template_id');
        }

        if($query->count() > 0){
            return $query->first();
        }

        return false;
    }

    /**
     * Выборка все отчетов
     *
     * @param bool $isJoinTemplate флаг - присоеденинять таблицу шаблонов или нет
     * @return bool|null|\stdClass
     */
    public function findReports($isJoinTemplate = true)
    {
        $query = $this->container->table('reports')
            ->select('reports.*');

        if($isJoinTemplate){
            $query->select($this->container->raw('templates.id as tmpId, templates.name, templates.user_id as tmp_userId'))
                ->select(['templates.hierarchy', 'templates.leftHierarchy', 'templates.determination', 'templates.type'])
                ->leftJoin('templates', 'templates.id', '=', 'reports.template_id');
        }

        if($query->count() > 0){
            return $query->get();
        }

        return false;
    }

    /**
     * Метод проверки существоания записи в матрице
     *
     * @param string $columnPath устаревшее в будущем будеи удалено - координаты в виде полных путей в столбце
     * @param string $rowPath устаревшее в будущем будеи удалено - координаты в виде полных путей в строке
     * @param int $x идентификатор ячейки строки
     * @param int $y идентификатор ячейки стобца
     * @param bool $isIdentityCheck пока что ограничевающий параметр для выборки
     * @param bool $isReturnedValue возвращать добавленное значение или статус успеха
     * @return bool
     */
    public function checkMatrixRecord($matrixStorangeName, $columnPath, $rowPath, $x, $y, $isIdentityCheck = true, $isReturnedValue = true)
    {
        if(!$this->checkTable($matrixStorangeName)){
            return false;
        }

        $checkRecord = $this->container->table($matrixStorangeName)
            ->select('value')
            ->where('column', '=', $columnPath)
            ->where('row', '=', $rowPath);

        /**
         * more precise definition of a record
         */
        if($isIdentityCheck){

            if(!is_numeric($x) || !is_numeric($y)){
                throw new InvalidParamException('Argument or several arguments are not numeric!');
            }

            /**
             * toDo: добавить проверку на основе идентифкаторов, а не путей
             */

            $checkRecord->where('xAxis', '=', $x)
                ->where('yAxis', '=', $y);
        }

        if($checkRecord->count() > 0){
            /**
             * if returned value, return `value` field from recordset
             * default state: true
             */
            return $isReturnedValue ? $checkRecord->first()->value : true;
        }

        return false;
    }

    /**
     * @param $tableName
     * @return bool
     */
    public function checkTable($tableName)
    {
        $checkTable = $this->container->pdo()
            ->query("SHOW TABLES LIKE '".$tableName."'");

        if($checkTable->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function showAllTables()
    {
        $query = 'SELECT TABLE_NAME 
                    FROM information_schema.TABLES
                   WHERE TABLE_SCHEMA = SCHEMA() /* = \'test\'*/
                     AND TABLE_NAME NOT LIKE \'%description%\'
                     AND TABLE_NAME NOT LIKE \'%templates%\'
                     AND TABLE_NAME NOT LIKE \'%\reports%\'';

        return $this->container->pdo()->query($query)->fetchAll();
    }
}