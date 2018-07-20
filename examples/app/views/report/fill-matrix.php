<?php
/**
 * @var $table Table
 * @var $description \app\models\Templates
 */
?>
<div class="row">
    <div class="col-md-12">
        <div class="block block-rounded block-opt-refresh-icon8">
            <div class="block-header">
                <h3 class="block-title"><span>Заполнение отчета на основе шаблона:</span> <i><?= $description->name; ?></i></h3>
            </div>
            <div class="block-content block-content-full">
                <div class="form-group">
                    <?= $table->render(); ?>
                </div>
            </div>
        </div>
    </div>
</div>