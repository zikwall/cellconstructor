<?php

namespace zikwall\cellconstructor\controllers;

use zikwall\cellconstructor\constructors\SimmulateTableConstructor;
use zikwall\cellconstructor\constructors\TableConstructor;
use zikwall\cellconstructor\helpers\Helper;
use core\Core;
use zikwall\cellconstructor\helpers\Json;

class TableController
{
    public function actionCreateTable()
    {
        $constructor = new TableConstructor();

        if(Core::$app->request->getIsPost()){
            $truePostArray = Helper::truePostArray($_POST['tableBody']);
            $creationTableFields = $constructor->getArrays($truePostArray);

            $tableName = $_POST['tableName'];

            return $constructor->getContentConstructor()->commandCreateTable($creationTableFields, $tableName,
                isset($_POST['onlyLastFields']) ? true : false);
        }

        return $this->render('create-table', [
            'templates' => $constructor->getContentContainer()->findTemplates(1)
        ]);
    }

    public function actionCreateMatrix()
    {
        if(!Core::$app->request->getIsPost()){
            header('HTTP/1.0 400 Bad Request', true, 400);
        }

        $constructor = new TableConstructor();

        $attributtes = $_POST['attributtes'];
        $template = $_POST['templateIdentity'];

        $json = Json::encode(['error' => false, 'message' => 'Failed create report!']);

        $contentTemplate = $constructor->getContentContainer()->findTemplate($template);

        if($constructor->getContentConstructor()->commandCreateMatrixStorange(
            $contentTemplate->hierarchy,
            $contentTemplate->leftHierarchy,
            $attributtes['tableName'],
            false
        )){
            $json = Json::encode(['error' => true, 'message' => 'Successfully!', 'name' => $attributtes['tableName']]);
        }

        echo $json;
    }

    public function actionCreateFlat()
    {
        if(!Core::$app->request->getIsPost()){
            header('HTTP/1.0 400 Bad Request', true, 400);
        }

        $constructor = new TableConstructor();

        $attributtes = $_POST['attributtes'];
        $template = $_POST['templateIdentity'];

        $json = Json::encode(['error' => false, 'message' => 'Failed create report!']);

        $contentTemplate = $constructor->getContentContainer()->findTemplate($template);

        if($constructor->getContentConstructor()->commandCreateTable(
            Json::decode($contentTemplate->hierarchy),
            $attributtes['tableName'],
            true, true
        )){
            $json = Json::encode(['error' => true, 'message' => 'Successfully!', 'name' => $attributtes['tableName']]);
        }

        echo $json;
    }

    public function actionOverviewHierarchy()
    {
        if(!Core::$app->request->getIsPost()){
            header('HTTP/1.0 400 Bad Request', true, 400);
        }
        unset($_POST['tbl_name']);
        unset($_POST['templateName']);
        unset($_POST['isCreateReport']);
        unset($_POST['reportName']);

        $truePostArray = Helper::truePostArray($_POST);

        $constructor = new SimmulateTableConstructor();
        $data = $constructor->getArrays($truePostArray);
        $tree = $constructor->arrayTree($data);
        echo $table = $constructor->simmulateFlatTable($tree, true)->render();
    }

    public function actionOverviewHierarchyArray()
    {
        if(!Core::$app->request->getIsPost()){
            header('HTTP/1.0 400 Bad Request', true, 400);
        }
        unset($_POST['tbl_name']);
        unset($_POST['templateName']);
        unset($_POST['isCreateReport']);
        unset($_POST['reportName']);
        $truePostArray = Helper::truePostArray($_POST);

        $constructor = new TableConstructor();
        $data = $constructor->getArrays($truePostArray);
        $tree = $constructor->arrayTree($data);
        Helper::p($tree);
    }

    public function actionPostArray()
    {
        if(!Core::$app->request->getIsPost()){
            header('HTTP/1.0 400 Bad Request', true, 400);
        }
        unset($_POST['tbl_name']);
        unset($_POST['templateName']);
        unset($_POST['isCreateReport']);
        unset($_POST['reportName']);
        $truePostArray = Helper::truePostArray($_POST);

        $constructor = new TableConstructor();
        $data = $constructor->getArrays($truePostArray);
        $tree = $constructor->arrayTree($data);
        Helper::p($tree);
    }

    public function actionOverviewMatrixArray()
    {
        if(!Core::$app->request->getIsPost()){
            header('HTTP/1.0 400 Bad Request', true, 400);
        }

        $truePostArray = Helper::truePostArray($_POST['up']);
        $truePostArrayRows = Helper::truePostArray($_POST['left']);
        $constructor = new TableConstructor();
        $up = $constructor->getArrays($truePostArray);
        $left = $constructor->getArrays($truePostArrayRows);
        $upTree = $constructor->arrayTree($up);
        $leftTree = $constructor->arrayTree($left);

        Helper::p($upTree);
        Helper::p($leftTree);
    }

    public function actionOverviewMatrix()
    {
        if(!Core::$app->request->getIsPost()){
            header('HTTP/1.0 400 Bad Request', true, 400);
        }

        $truePostArray = Helper::truePostArray($_POST['up']);
        $truePostArrayRows = Helper::truePostArray($_POST['left']);

        $constructor = new SimmulateTableConstructor();

        $up = $constructor->getArrays($truePostArray);
        $left = $constructor->getArrays($truePostArrayRows);
        $upTree = $constructor->arrayTree($up);
        $leftTree = $constructor->arrayTree($left);

        echo $table = $constructor->simmulateMatrxiTable($upTree, $leftTree, true)->render();
    }

    public function actionOverviewHierarchyRows()
    {
        if(!Core::$app->request->getIsPost()){
            header('HTTP/1.0 400 Bad Request', true, 400);
        }

        unset($_POST['tbl_name']);
        unset($_POST['templateName']);
        unset($_POST['isCreateReport']);
        unset($_POST['reportName']);
        $truePostArray = Helper::truePostArray($_POST);

        $constructor = new TableConstructor();
        $left = $constructor->getArrays($truePostArray);
        $leftTree = $constructor->arrayTree($left);
        echo $table = $constructor->drawRowsTable($leftTree)->render();
    }
}