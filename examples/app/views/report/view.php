<?php
/**
 * @var $description stdClass
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
                <div class="row">
                    <div class="col-md-12">
                        <a class="dropdown-toggle pull-left"
                           title="Показать подробности"
                           onclick="$('#viewdescription').slideToggle('fast');$('#viewdescription').focus();return false;"
                           data-toggle="dropdown" href="#"
                           aria-label="Показать подробности"
                           aria-haspopup="true"
                           aria-expanded="false">Показать подробности
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <div id="viewdescription" style="display: none;">
                            <hr>
                            <div class="col-md-12">
                                <div class="panel panel-default my_panel">
                                    <div class="panel-heading">
                                        <strong>Подробности</strong> отчета
                                    </div>
                                    <div class="panel-body">
                                        <b>Название отчета:</b> <?= $description->report_name; ?><br>
                                        <b>Название шаблона:</b> <?= $description->name; ?><br>
                                        <b>Название таблицы:</b> <?= $description->table_name; ?><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="col-md-12">
                        <form id="matrixForm">
                            <div class="form-group pull-left">
                                <button type="button" name="printReport" id="printReport" onclick="window.open('/report/print/id/<?= $description->id; ?>', '_blank');"
                                        data-identity="<?= $description->id; ?>"
                                        data-template="<?= $description->tempId; ?>" class="btn btn-secondary">Печать</button>
                            </div>
                            <div class="form-group pull-right">
                                <?php
                                $location = '/report/fill/'.($description->type == 1 ? 'flat' : 'matrix').'/id/'.$description->id;
                                ?>
                                <button type="button" name="editReport" id="editReport" onclick="window.open('<?= $location; ?>', '_blank');"
                                        data-identity="<?= $description->id; ?>"
                                        data-template="<?= $description->tempId; ?>" class="btn btn-secondary">Заполнить <?= $description->type == 1 ? 'табличный' : 'матричный'; ?> отчет
                                </button>
                                <button type="button" name="editTemplate" id="editTemplate"
                                        data-identity="<?= $description->id; ?>"
                                        data-template="<?= $description->tempId; ?>"
                                        class="btn btn-success">Редактировать <?= $description->type == 1 ? 'табличный' : 'матричный'; ?> шаблон
                                </button>
                                <button onclick="$(location).attr('href','/template/view/id/<?= $description->tmpId; ?>/type/<?= $description->type; ?>')" type="button" name="viewTemplate"
                                        id="viewTemplate"
                                        data-identity="<?= $description->id; ?>"
                                        data-template="<?= $description->tmpId; ?>" class="btn btn-primary">Посмотреть <?= $description->type == 1 ? 'табличный' : 'матричный'; ?> шаблон
                                </button>
                            </div>
                            <div class="form-group">
                                <?= $table->render(); ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#printReport').bind('.btn-secondary', function() {

        var url = $('button', this).prop('href');
        var target = $('button', this).prop('target');

        if(url) {
            // # open in new window if "_blank" used
            if(target == '_blank') {
                window.open(url, target);
            } else {
                window.location = url;
            }
        }
    });
</script>
