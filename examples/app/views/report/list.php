<?php
/**
 * @var $reports []
 */
?>

<div class="row gutters-tiny js-appear-enabled animated fadeIn" data-toggle="appear">
    <?php foreach($reports as $report): ?>
        <div class="col-4 col-md-6 col-xl-6">
            <a class="block block-link-shadow text-center" href="/report/view/id/<?= $report->id; ?>">
                <div class="block-content ribbon ribbon-bookmark ribbon-success ribbon-left">
                    <p class="mt-5">
                        <i class="fa fa-table fa-3x"></i>
                    </p>
                    <p class="font-w600"><?= $report->report_name; ?></p>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>

