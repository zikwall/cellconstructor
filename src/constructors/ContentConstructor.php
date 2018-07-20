<?php

namespace zikwall\cellconstructor\constructors;

use zikwall\cellconstructor\constructors\interfaces\ContentConstructorInterface;
use zikwall\cellconstructor\containers\ContentContainer;
use zikwall\cellconstructor\helpers\Helper;
use zikwall\cellconstructor\exceptions\InvalidParamException;
use zikwall\cellconstructor\helpers\Json;
use Pixie\QueryBuilder\QueryBuilderHandler;

class ContentConstructor implements ContentConstructorInterface
{
    use ArrayableTrait;

    /**
     * @var ContentContainer
     */
    public $contentContainer;

    /**
     * ContentConstructor constructor.
     */
    public function __construct()
    {
        $this->contentContainer = new ContentContainer();
    }

    public function getContainer() : ContentContainer
    {
        return $this->contentContainer;
    }

    /**
     * @return array commands
     */
    public function commands() : array
    {
        return [];
    }

    /**
     * @param $creationTableFields
     * @param $creationTableName
     * @param bool $isOnlyLastLevelCreation
     * @param null $db
     * @return bool
     */
    public function commandCreateTable(array $creationTableFields, string $creationTableName, bool $isOnlyLastLevelCreation = true, bool $isUsingTemplateHiararchy = false, string $db = null) : bool
    {
        if(empty($creationTableName)){
            throw new InvalidParamException('Required attribute: "table name" is missing!');
        }

        if(!is_array($creationTableFields)){
            throw new InvalidParamException('Required attribute: "table fields" is not array!');
        }

        if(!empty($db)){
            $this->getContainer()->container->select_db($db);
        }

        /*
         * Only last level element create? Checking condition result:
         *  1. TRUE - use is the last levels fields
         *  2. FALSE - use is the all fields
         */
        if($isOnlyLastLevelCreation){
            $creationTableFields = $this->getArrayTreeLastLevelElements($this->arrayTree($creationTableFields));
        } elseif($isUsingTemplateHiararchy) {
            $creationTableFields = $this->getArrayTreeLastLevelElements($creationTableFields);
        }

        $createTableQueryBuildingString = '';
        $createTableBodyQueryBuildingString = '';

        $createTableQueryBuildingString .= 'CREATE TABLE IF NOT EXISTS ';
        $createTableQueryBuildingString .= Helper::apostrophe($creationTableName);
        $createTableQueryBuildingString .= ' ( ';

        foreach ($creationTableFields as $field){
            /*
             * create field, an example: "
             *
             * ```sql
             *  `name` varchar
             * ```
             */
            $createTableBodyQueryBuildingString .= Helper::apostrophe($field['field_name']) . ' ' . $field['field_type'];

            /*
             * checking lenght, is true create lenght parametr, an example of with previous QueryString:
             *
             * ```sql
             * `name` varchar(255)
             * ```
             */
            if (!empty($field['lenght'])) {
                $createTableBodyQueryBuildingString .= ' ' . Helper::brackets($field['lenght']);
            }

            /*
             * checking the default value. toDo: In the under construction state
             */
            if (!empty($field['default']) || $field['null']) {
                if (!empty($field['default'])) {
                    $createTableBodyQueryBuildingString .= ' NOT NULL DEFAULT \'' . $field['default'] . '\' ';
                } elseif ($field['null'] && empty($field['default'])) {
                    $createTableBodyQueryBuildingString .= ' DEFAULT NULL ';
                } elseif ($field['null']) {
                    $createTableBodyQueryBuildingString .= ' DEFAULT NULL ';
                }
            } else {
                $createTableBodyQueryBuildingString .= ' NOT NULL ';
            }

            /*
             * checking the comment for field. toDo: In the under construction state
             */
            if (!empty($field['comment'])) {
                $createTableBodyQueryBuildingString .= ' COMMENT \'' . $field['comment'] . '\',';
            } else {
                $createTableBodyQueryBuildingString .= ',';
            }
        }

        /*
         * delete last comma(,) symbol from QueryString
         */
        $createTableBodyQueryBuildingString = substr($createTableBodyQueryBuildingString, 0, -1);
        /*
         * union "head" and "body" QueryStrings
         */
        $createTableQueryBuildingString .= $createTableBodyQueryBuildingString;
        /*
         * add last params string
         */
        $createTableQueryBuildingString .= ' ) ENGINE=InnoDB DEFAULT CHARSET=utf8';

        /*
         * return FullQuerySting, an example of:
         *
         * ```sql
         * CREATE TABLE IF NOT EXISTS `newTableName` (
         *  `a` varchar (233) NOT NULL ,
         *  `b` int (34434) NOT NULL ,
         *  `c` varchar (34343) NOT NULL ,
         *  `d` int (4343) NOT NULL ,
         *  `e` varchar (43434) NOT NULL ,
         *  `f` int (343) NOT NULL ,
         *  `g` varchar (43434) NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8
         * ```
         *
         * ```php
         * echo $createTableQueryBuildingString;
         * ```
         */

        /**
         * toDo:
         *
         * ```php
         *  $this->getContainer()->container->transaction(function (QueryBuilderHandler $db) use ($createTableQueryBuildingString) {
         *      $db->query($createTableQueryBuildingString)->get();
         *      $db->commint();
         *      $db->rollback();
         * });
         * ```
         */

        /**
         * PDO instance
         */
        $pdo = $this->getContainer()->container->pdo();

        try {
            $pdo->beginTransaction();
            $pdo->query($createTableQueryBuildingString)->execute();
            $pdo->commit();
            $checking = true;
        } catch (\PDOException $e) {
            $checking = false;
            print_r($e->errorInfo);
            $pdo->rollBack();
        }

        if($checking){
            return true;
        }

        return false;
    }

    /**
     * @param array $y up array
     * @param array $x left array
     * @param $tableName string table name
     * @param bool $isInsert
     * @param bool $isReturnedInsertIds flag return inserting ids or true/false
     * @return bool|string
     */
    public function commandCreateMatrixStorange(array $y = [], array $x = [], string $tableName, bool $isInsert = true, bool $isReturnedInsertIds = false)
    {
        if(empty($tableName)){
            $tableName = 'm_report_'.md5('hash');
        }

        if($this->getContainer()->checkTable($tableName)){
            return Json::encode(['status' => 'COMMAND_FAILED', 'message' => 'Table '.$tableName.' is exist!']);
        }

        /**
         * BEGIN BLOCK: this block collects the query string, to create a matrix storange
         */
        $createTableBodyQueryBuildingString = '';
        $createTableQueryBuildingString = 'CREATE TABLE IF NOT EXISTS ';
        $createTableQueryBuildingString .= Helper::apostrophe($tableName);
        $createTableQueryBuildingString .= ' ( ';

        $createTableBodyQueryBuildingString .= '`id` int NOT NULL AUTO_INCREMENT,';
        $createTableBodyQueryBuildingString .= '`xAxis` int(100) NOT NULL,';
        $createTableBodyQueryBuildingString .= '`yAxis` int(100) NOT NULL,';
        $createTableBodyQueryBuildingString .= '`column` varchar(255) NOT NULL,';
        $createTableBodyQueryBuildingString .= '`row` varchar(255) NOT NULL,';
        $createTableBodyQueryBuildingString .= '`value` text NOT NULL,';
        $createTableBodyQueryBuildingString .= '`isHeader` int(1) NOT NULL DEFAULT \'0\',';

        $createTableQueryBuildingString .= $createTableBodyQueryBuildingString;
        $createTableQueryBuildingString .= 'PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        /**
         * END BLOCK: end the collect query string
         */

        $pdo = $this->getContainer()->container->pdo();

        try {
            $pdo->beginTransaction();
            $pdo->query($createTableQueryBuildingString)->execute();
            $pdo->commit();
            $checking = true;
        } catch (\PDOException $e) {
            $checking = false;
            print_r($e->errorInfo);
            $pdo->rollBack();
        }

        /**
         * Check transaction state, if true continue
         */
        if($checking){
            // checking inserting datas
            if($isInsert){
                $batchInsertingCounter = 0;
                /**
                 * @var [] $batchInsertData stored insert data elements for single array, an example:
                 * $data = [
                 *  [
                 *      'name'        => 'Sana',
                 *      'description' => 'Blah'
                 *  ],
                 *  [
                 *      'name'        => 'Usman',
                 *      'description' => 'Blah'
                 *  ],
                 * ];
                 */
                $batchInsertData = [];

                /**
                 * create matrix types data elements
                 */
                foreach ($y as $column) {
                    foreach ($x as $row){
                        /**
                         * checking x axis element on state heading or not, states: 0 - is heading, 1 - is the not heading
                         */
                        if ($row['heading'] != 0){
                            $batchInsertData[$batchInsertingCounter]['yAxis'] = $column['id'];
                            $batchInsertData[$batchInsertingCounter]['xAxis'] = $row['id'];
                            $batchInsertData[$batchInsertingCounter]['column'] = trim($column['path']);
                            $batchInsertData[$batchInsertingCounter]['row'] = trim($row['path']);
                            $batchInsertingCounter++;
                        }
                    }
                }

                /**
                 * Execution SQL Query, in case of batch insert, it will return an array of insert ids.
                 * @var array $insertIds
                 */
                if($insertIds = $this->getContainer()->container->table($tableName)->insert($batchInsertData)){
                    return $isReturnedInsertIds ? $insertIds : true;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param $templateName
     * @param $upHierarchy
     * @param $leftHierarchy
     * @param bool $isReturnedInsertId
     * @param int $type
     * @param string $charset
     * @param string $engine
     * @return array|bool|string
     */
    public function commandSaveMatrixTemplate($templateName, $upHierarchy = [], $leftHierarchy = [], $isReturnedInsertId = false, $type = 2, $charset = 'utf8', $engine = 'InnoDb')
    {
        if(empty($upHierarchy) || empty($leftHierarchy)){
            throw new InvalidParamException('One or more required attributes are not array or empty!');
        }

        $upHierarchyToJson = Json::encode($upHierarchy);
        $leftHierarchyToJson = Json::encode($leftHierarchy);

        if(empty($templateName)){
            throw new InvalidParamException('Template name can not be null!');
        }

        if($insertId = $this->getContainer()->container->table('templates')->insert([
            'name' => $templateName,
            'user_id' => 1, // toDo create user sessions
            'type' => $type,
            'hierarchy' => $upHierarchyToJson,
            'leftHierarchy' => $leftHierarchyToJson,
            'charset' => $charset,
            'engine' => $engine
        ])){
            return $isReturnedInsertId ? $insertId : true;
        }

        return false;
    }

    /**
     * @param $templateName
     * @param $hierarchy
     * @param bool $isReturnedInsertId
     * @param int $type
     * @param string $charset
     * @param string $engine
     * @return array|bool|string
     */
    public function commandSaveFlatTemplate($templateName, $hierarchy, $isReturnedInsertId = false, $type = 1, $charset = 'utf8', $engine = 'InnoDb')
    {
        if(empty($templateName)){
            throw new InvalidParamException('Template name can not be null!');
        }

        if(empty($hierarchy)){
            throw new InvalidParamException('The main hierarchy[] can not be empty array!');
        }

        $determination = $this->createDetermination($hierarchy);

        $getArray = $this->getArrays($hierarchy);
        $hierarchy = $this->arrayTree($getArray);
        $hierarchyToJson = Json::encode($hierarchy);

        if($insertId = $this->getContainer()->container->table('templates')->insert([
            'name' => $templateName,
            'user_id' => 1,
            'type' => $type,
            'hierarchy' => $hierarchyToJson,
            'determination' => $determination,
            'charset' => $charset,
            'engine' => $engine
        ])){
            return $isReturnedInsertId ? $insertId : true;
        }

        return false;
    }

    /**
     * @param $tableName
     * @param $reportName
     * @param $templateIdentity
     * @param bool $isReturnedInsertId
     * @return array|bool|string
     */
    public function commandSaveReport($tableName, $reportName, $templateIdentity, $isReturnedInsertId = false)
    {
        if(empty($tableName)){
            throw new InvalidParamException('Table name can not be null!');
        }

        if($insertId = $this->getContainer()->container->table('reports')->insert([
            'table_name' => $tableName,
            'user_id' => 1,
            'template_id' => $templateIdentity,
            'report_name' => $reportName
        ])){
            return $isReturnedInsertId ? $insertId : true;
        }

        return false;
    }

    /**
     * Метод создает идентифицационную привязку к полям физических таблицы.
     * Данный метод нужен для генерирования "плоских" отчетов на основу одномерных таблиц
     *
     * @param [] $hierarchy
     * @return string json array
     */
    public function createDetermination(array $hierarchy = [])
    {
        $determinationLink = [];

        $data = $this->getArrays($hierarchy);
        $tree = $this->arrayTree($data);

        /**
         * get the latest level elements for creating a reference definition of a physical table and a description
         */
        $lasts = $this->getArrayTreeLastLevelElements($tree);

        /**
         * create array of binding links
         */
        foreach ($lasts as $last => $element){
            /**
             * each link corresponds to the real name of the field in the flat physical table
             * and the full path of the template of the same position
             */
            $determinationLink[$element['id']]['column'] = $element['field_name'];
            $determinationLink[$element['id']]['path'] = $element['path'];
        }

        /**
         * returned json formar array, for save in templates `determination` field
         */
        return Json::encode($determinationLink);
    }
}