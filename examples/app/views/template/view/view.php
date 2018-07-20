<?php
/**
 * @var $table Table
 */
?>
<div class="row">
    <div class="col-md-12">
        <div class="block block-rounded block-opt-refresh-icon8">
            <div class="block-header">
                <h3 class="block-title"><span>Шаблон отчетов:</span> <i><?= $description->name; ?></i></h3>
            </div>
            <div class="block-content block-content-full">
                <form id="matrixForm">
                    <div class="form-group pull-right">
                        <?php
                        $location = $description->type == 1 ? '/report/create/matrix/'.$description->id : '/report/create/flat/'.$description->id;
                        ?>
                        <button onclick="$(location).attr('href',<?= $location; ?>');" type="button" name="createReport" id="createReport"
                                data-identity="<?= $description->id; ?>"
                                data-type="<?= $description->type; ?>"
                                class="btn btn-success">Создать <?= $description->type == 1 ? 'табличный' : 'матричный'; ?> отчет на основе этого шаблона
                        </button>
                        <button type="button" name="editTemplate" id="editTemplate"
                                data-identity="<?= $description->id; ?>"
                                data-type="<?= $description->type; ?>"
                                class="btn btn-primary">Редактировать <?= $description->type == 1 ? 'табличный' : 'матричный'; ?> шаблон
                        </button>
                        <button type="button" name="createThisTable" id="createThisTable"
                                data-identity="<?= $description->id; ?>"
                                data-type="<?= $description->type; ?>"
                                class="btn btn-success">Создать <?= $description->type == 1 ? 'табличную' : 'матричную'; ?> таблицу
                        </button >
                    </div>
                    <div class="form-group">
                        <?= $table->render(); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    <?php
    foreach ($tables as $table){
        $tableList .= '<option value="'.$table['TABLE_NAME'].'">'.$table['TABLE_NAME'].'</option>';
    }

    $type = $description->type == 1 ? 'flat' : 'matrix';

    ?>

    $('#createThisTable').click(function () {
        $.confirm({
            title: 'Create Table',
            content: '' +
            '<div class="row">' +
            '    <div class="col-md-6">' +
            '        <div class="bg-image" style="background-image: url(\'/public/assets/img/1.png\');">' +
            '            <div class="bg-white-op-90">' +
            '                <div class="content content-full content-top">' +
            '                    <h2 class="py-50 text-center">Привет, хочешь создать таблицу? Ну так дерзай!!</h2>' +
            '                </div>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    <div class="col-md-6">' +
            '<form id="eventForm" action="" class="formName">' +
            '<div class="form-group">' +
            '<label>Введите название таблицы</label>' +
            '<input id="tableName" name="tableName" type="text" placeholder="Название таблицы" class="name form-control" required />'+
            '</div>' +
            '</form>'+
            '    </div>' +
            '</div>',
            columnClass: 'col-md-12',
            buttons: {
                create: {
                    text: 'Создать таблицу',
                    action: function(){
                        if($('#tableName').val() == ''){
                            $.alert('Table name is required!');
                        } else {
                            $.ajax({
                                type: 'POST',
                                url: '/table/create/<?= $type; ?>',
                                dataType: 'json',
                                data: {
                                    attributtes: $('#eventForm').serializeObject(),
                                    templateIdentity: <?= $description->id; ?>
                                },
                                success: function (response) {
                                    if(response.error){
                                        $.alert('Successfully! Create table: ' + response.name);
                                    } else {
                                        $.alert(response.message);
                                    }
                                }
                            });
                        }
                    }
                },
                cancel: function () {
                    //close
                },
            }
        });
    });

     var $lastReportName = '';
     var $isCreated = false;

    /**
     * an event handler click to create report button
     */
    $('#createReport').click(function () {
        $.confirm({
            title: 'Create Report',
            content: '' +
            '<div class="row">' +
            '    <div class="col-md-6">' +
            '        <div class="bg-image" style="background-image: url(\'/public/assets/img/1.png\');">' +
            '            <div class="bg-white-op-90">' +
            '                <div class="content content-full content-top">' +
            '                    <h2 class="py-50 text-center">Привет, хочешь создать таблицу? Ну так дерзай!!</h2>' +
            '                </div>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    <div class="col-md-6">' +
                    '<form id="eventForm" action="" class="formName">' +
                        '<div class="form-group">' +
                            '<label>Введите название отчета</label>' +
                            '<input id="reportName" name="reportName" type="text" placeholder="Название отчета" class="name form-control" required />'+
                        '</div>' +
                        '<div class="form-group">' +
                            '<label>Введите название таблицы</label>' +
                            '<select id="tableName" name="tableName" class="name form-control" required>' +
                                '<option value="-1">Выберите таблицу</option>' +
                                ' <?= $tableList; ?> ' +
                            '</select>' +
                        '</div>'+
                    '</form>'+
            '    </div>' +
            '</div>',
            columnClass: 'col-md-12',
            buttons: {
                create: {
                    text: 'Создать шаблон',
                    action: function(){
                        if($('#tableName').val() == '' || $('#reportName').val() == '' || $('#tableName').val() == '-1'){
                            if($('#reportName').val() == ''){
                                $.alert('Report name is required!');
                            }

                            if($('#tableName').val() == '' || $('#tableName').val() == '-1'){
                                $.alert('Table name is required!');
                            }
                        } else {
                            if($isCreated){
                                /*
                                 * toDo: check last creation report
                                 *
                                    if($lastReportName == data.name){
                                        // logic
                                    }
                                */
                            }

                            $.ajax({
                                type: 'POST',
                                url: '/report/create',
                                dataType: 'json',
                                data: {
                                    attributtes: $('#eventForm').serializeObject(),
                                    templateIdentity: <?= $description->id; ?>
                                },
                                success: function (response) {
                                    if(response.error){

                                        var $insert = response.insert;

                                        $isCreated = true;
                                        $lastReportName = response.name;

                                        $.alert({
                                            title: 'Успех!',
                                            content: 'Желаете заполнить отчет?',
                                            buttons: {
                                                yes: {
                                                    text: 'Да',
                                                    action: function () {
                                                        $(location).attr('href', '/report/fill/<?= $type; ?>/id/' + $insert)
                                                    }
                                                },
                                                no: {
                                                    text: 'Нет',
                                                    action: function () {
                                                        //close
                                                    }
                                                }
                                            },
                                        });
                                    } else {
                                        $.alert(response.message);
                                    }
                                }
                            });
                        }
                    }
                },
                cancel: function () {
                    //close
                },
            }
        });
    });
</script>