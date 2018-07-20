<?php

namespace app\controllers;

use zikwall\cellconstructor\constructors\TableConstructor;
use zikwall\cellconstructor\helpers\Helper;
use zikwall\cellconstructor\helpers\Json;
use Pixie\QueryBuilder\QueryBuilderHandler;

/**
 * Данный контроллер предназначени для работы над отчетами - создание, заполнение, удаление редактирование.
 * Отчет - это сгенерированный на основе какого-либо произвольного шаблона.
 *
 * Class ReportController
 * @package app\controllers
 */
class ReportController
{
    public $insertId;
    public $updateId;

    /**
     * Действие предназначено для вывода на печать отчета
     *
     * @param $id int дентификатор отчета
     * @return mixed
     */
    public function actionPrint($id)
    {
        $this->setLayout('print');

        $constructor = new TableConstructor();

        $report = $constructor->getContentContainer()->findReport($id);

        if($report->type == 1){
            $table = $constructor->drawFlatTable(Json::decode($report->hierarchy), Json::decode($report->determination), $report->table_name);
        } elseif($report->type == 2){
            $table = $constructor->drawMatrixTable(Json::decode($report->hierarchy), Json::decode($report->leftHierarchy), $report->table_name);
        }

        return $this->render('print',[
            'table' => $table,
            'description' => $report,
        ]);
    }

    /**
     * @return mixed Действие выводит список отчетов, с возможностью дальнейшего редактирование выбранного отчета
     */
    public function actionList()
    {
        $constructor = new TableConstructor();
        $reports = $constructor->getContentContainer()->findReports();

        return $this->render('list', [
            'reports' => $reports
        ]);
    }

    /**
     * Просмотр одного из отетов
     *
     * @param int $id идентификатор отчета
     * @return mixed
     */
    public function actionView($id)
    {
        $constructor = new TableConstructor();
        $report = $constructor->getContentContainer()->findReport($id);

        /**
         * Генерация отчета на основе типа шаблона: матричный или плоский
         */
        if($report->type == 1){
            $table = $constructor->drawFlatTable(Json::decode($report->hierarchy), Json::decode($report->determination), $report->table_name);
        } elseif($report->type == 2){
            $table = $constructor->drawMatrixTable(Json::decode($report->hierarchy), Json::decode($report->leftHierarchy), $report->table_name);
        }

        return $this->render('view', [
            'description' => $report,
            'table' => $table
        ]);
    }

    /**
     * Метод для создания отчетов
     */
    public function actionCreate()
    {
        if(!Core::$app->request->getIsPost()){
            header('HTTP/1.0 400 Bad Request', true, 400);
        }

        $json = Json::encode(['error' => false, 'message' => 'Failed create report!']);

        $constructor = new TableConstructor();
        /**
         * атрибуты для заполнения, название отчета, название хранилища данных отчета
         */
        $attributtes = $_POST['attributtes'];
        /**
         * шаблон по которому создавался отчет
         */
        $template = $_POST['templateIdentity'];

        if($insertId = $constructor->getContentConstructor()->commandSaveReport($attributtes['tableName'], $attributtes['reportName'], $template, true)){
            $json = Json::encode(['error' => true, 'message' => 'Successfully!', 'insert' => $insertId, 'name' => $attributtes['reportName']]);
        }

        /**
         * возвращается ответ в виде json массива для обработки на клиента ajax-ом
         */
        echo $json;
    }

    /**
     * Действие для работы с ячейками матрицы
     */
    public function actionUpdateMatrixCell()
    {
        if(!Core::$app->request->getIsPost()){
            header('HTTP/1.0 400 Bad Request', true, 400);
        }

        /**
         * Экшн обновления/вставки данных в матричнную физическую таблицу
         * Данный метод нуждается в жуткой доработке, но пока что работает
         */

        $constructor = new TableConstructor();
        $container = $constructor->getContentContainer();

        $matrixStorangeName = $_POST['container'];
        /**
         *  Todo: создать идентификацию на основе числовых значений, а именно id элементов
         */
        $column = $_POST['colPath']; //obsolete - deleted next
        $row    = $_POST['rowPath']; //obsolete - deleted next
        $colId  = $_POST['colId'];
        $rowId  = $_POST['rowId'];
        $value  = $_POST['value'];
        //Helper::p($_POST);

        /**
         * Если физической таблицы не существует выполняем команду на ее создание, toDo: может и не надо
         */
        //if($container->checkTable($matrixStorangeName)) {
            //if(!$matrixStorangeName = $constructor->getContentConstructor()->commandCreateMatrixStorange(null, null, $matrixStorangeName, false)){
               //echo 'Failed create matrix stotrange';
            //}
        //}

        /**
         * Если запись существует, выполняем операцию обновления, иначе же создаем новую запись
         */
        if(!empty($container->checkMatrixRecord($matrixStorangeName, $column, $row, null, null, false, false))){

            /**
             * Begin transaction for safe UPDATE query
             */
            $update = $container->container->transaction(function (QueryBuilderHandler $db) use ($rowId, $row, $colId, $column, $matrixStorangeName, $value){
                    $this->updateId = $db->table($matrixStorangeName)
                        // refractor db tables
                        //->where('xAxis', $rowId) // uncomment
                        //->where('yAxis', $colId) // uncomment
                        ->where('column', '=', $column) // deleted
                        ->where('row', '=', $row) // deleted
                        ->update([
                            'value' => $value
                        ]);

                // uncomment return
                //return true;
            });

            if($update){
                echo Helper::brackets('Succsessfully Update!');
            }

        } else {
            /**
             * Begin transaction for safe INSERT query
             */
            $insert = $container->container->transaction(function (QueryBuilderHandler $db) use ($column, $row, $colId, $rowId, $value, $matrixStorangeName){
                $this->insertId = $db->table($matrixStorangeName)->insert([
                    'xAxis' => $rowId,
                    'yAxis' => $colId,
                    'row' => $row,
                    'column' => $column,
                    'value' => $value
                ]);

                // uncomment return
                //return true;
            });

            if($insert){
                echo Helper::brackets('Succsessfully Insert! Insert ID: ' . $this->insertId);
            }
        }

    }

    public function actionFillMatrixReport($reportIdentity)
    {
        $constructor = new TableConstructor();
        $report = $constructor->getContentContainer()->findReport($reportIdentity);

        $table = $constructor->drawMatrixTable(Json::decode($report->hierarchy), Json::decode($report->leftHierarchy), $report->table_name, true);

        return $this->render('fill-matrix', [
            'table' => $table,
            'description' => $report
        ]);
    }

    public function actionCreateFlatReport()
    {
        /**
         * toDo: this old compare method
         */
        $constructor = new TableConstructor();

        if(Core::$app->request->getIsPost()){
            /**
             * Create report with physical table?
             */
        }

        if(!empty($_GET['template']) && Core::$app->request->getIsGet()){
            $template = $constructor->getContentContainer()->findTemplate($_GET['template'], 1);
        }

        return $this->render('create-flat', [
            'templates' => $constructor->getContentContainer()->findTemplates(1)
        ]);
    }

    public function actionCreateMatrixReport()
    {
        /**
         * toDo: this old compare method
         */
        $constructor = new TableConstructor();

        if(Core::$app->request->getIsPost()){
            /**
             * Create report with physical table?
             */
        }

        return $this->render('create-matrix', [
            'templates' => $constructor->getContentContainer()->findTemplates(2)
        ]);
    }
}