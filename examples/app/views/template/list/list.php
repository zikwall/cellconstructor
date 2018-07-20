<?php
/**
 * @var $templates stdClass
 */

?>
<div class="row gutters-tiny js-appear-enabled animated fadeIn" data-toggle="appear">
    <?php foreach($templates as $template): ?>
    <div class="col-9 col-md-6 col-xl-6">
        <a class="block block-link-shadow text-center" href="/template/view/id/<?= $template->id; ?>/type/<?= $template->type; ?>">
            <div class="block-content ribbon ribbon-bookmark ribbon-success ribbon-left">
                <p class="mt-5">
                    <i class="fa fa-table fa-3x"></i>
                </p>
                <p class="font-w600"><?= $template->name; ?></p><p class="font-w600"><?= $template->type == 1 ? '(Табличный)': '(Матричный)'; ?></p>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>
