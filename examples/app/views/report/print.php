<?php
/**
 * @var $table \app\components\cellbrush\Table\Table
 */
?>
<div class="row">
    <div class="col-md-12">
        <div class="block block-rounded block-opt-refresh-icon8">
            <div class="block-header">
                <h3 class="block-title"><span>Отчет:</span> <i><?= $description->report_name; ?></i></h3>
            </div>
            <div class="block-content block-content-full">
                <?= $table->render(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        window.print();
    });
</script>
