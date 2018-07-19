<?php

require_once '../../../../vendor/autoload.php';

use zikwall\cellconstructor\helpers\Json;
use zikwall\cellconstructor\helpers\Helper;
use zikwall\cellconstructor\ConstructorConfiguration;

?>

<style>
    table, th, td {
        border: 1px solid black;
    }
</style>

<?php

/**
 * This is example usege simmulations hierarchical tables from dataset
 *
 * left dataset @see autocarsLeftArray.json
 * up dataset @see autocarsUpArray.json
 */

ConstructorConfiguration::getInstance()->init();
$dir = \zikwall\cellconstructor\ConstructorComponent::getExamplesDir();
$constructtor = new \zikwall\cellconstructor\constructors\SimmulateTableConstructor();

// get php arrays from JSON dataset
$up = Json::decode(file_get_contents($dir . '/autocarsUpArray.json'));
$left = Json::decode(file_get_contents($dir . '/autocarsLeftArray.json'));

$upH = $constructtor->getArrays($constructtor->arrayTree($up));
$leftH = $constructtor->getArrays($constructtor->arrayTree($left));

// print last level elements in up hierarchy
//Helper::p($constructtor->getArrayTreeLastLevelElements($upH));

// output "flat" hierarchical table
echo $constructtor->simmulateFlatTable($upH, true)->render();

echo PHP_EOL, PHP_EOL, '<br>', '<br>', '<hr>', '<br>';

// output matrix table with left and up hierarcht and default dataset
echo $constructtor->simmulateMatrxiTable($upH, $leftH, true)->render();