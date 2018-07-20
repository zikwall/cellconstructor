<?php

namespace zikwall\controllers;

use zikwall\cellconstructor\constructors\SimmulateTableConstructor;
use zikwall\cellconstructor\constructors\TableConstructor;
use zikwall\cellconstructor\containers\ContentContainer;
use zikwall\cellconstructor\helpers\Helper;
use core\Core;
use zikwall\cellconstructor\exceptions\InvalidParamException;
use zikwall\cellconstructor\helpers\Json;

class TemplateController
{
    public function actionList()
    {
        $this->setLayout('layout');

        return $this->render('list\list', [
            'templates' => (new ContentContainer())->findTemplates()
        ]);
    }

    public function actionCreateMatrixTemplate()
    {
        if(Core::$app->request->getIsPost()){
            $templateName = $_POST['templateName'];

            $truePostArray = Helper::truePostArray($_POST['up']);
            $truePostArrayRows = Helper::truePostArray($_POST['left']);

            $constructor = new TableConstructor();

            $up = $constructor->getArrays($truePostArray);
            $left = $constructor->getArrays($truePostArrayRows);
            $upTree = $constructor->arrayTree($up);
            $leftTree = $constructor->arrayTree($left);

            if($constructor->getContentConstructor()->commandSaveMatrixTemplate($templateName, $upTree, $leftTree)){
                return Helper::brackets('Successfully save matrix template! Template name: ' . $templateName);
            } else {
                return Helper::brackets('Failed save matrix template... :(');
            }
        }

        return $this->render('create/matrix', []);
    }

    public function actionCreateFlatTemplate()
    {
        if(Core::$app->request->getIsPost()){
            //Helper::p(Helper::truePostArray($_POST['form']));

            $constructor = new TableConstructor();
            $truePostArray = Helper::truePostArray($_POST['form']);

            if($constructor->getContentConstructor()->commandSaveFlatTemplate($_POST['templateName'], $truePostArray)){
                return Helper::brackets('Successfully save template! Template name: ' . $_POST['templateName']);
            }
        }

        return $this->render('create/flat', []);
    }

    public function actionView($templateId, $type)
    {
        $constructor = new SimmulateTableConstructor();
        $content = $constructor->getContentContainer()->findTemplate($templateId, $type);

        if(!$content){
            throw new InvalidParamException('Container is empty!');
        }

        if($type == 1){
            $table = $constructor->simmulateFlatTable(Json::decode($content->hierarchy));
        } elseif($type == 2){
            $table = $constructor->simmulateMatrxiTable(Json::decode($content->hierarchy), Json::decode($content->leftHierarchy));
        }

        return $this->render('view\view', [
            'table' => $table,
            'description' => $content,
            'tables' => $constructor->getContentContainer()->showAllTables()
        ]);
    }

    public function actionViewUserTemplate($userId, $templateId, $type)
    {
        // toDo
    }
}