<?php
/**
 * @var $templates stdClass
 */
?>

<div class="row gutters-tiny js-appear-enabled animated fadeIn" data-toggle="appear">
    <div class="col-9 col-md-6 col-xl-6">
        <a class="block block-link-shadow text-center" href="javascript::void(0)" name="overviewCommonTableStructure" id="overviewCommonTableStructure">
            <div class="block-content ribbon ribbon-bookmark ribbon-success ribbon-left">
                <p class="mt-5">
                    <i class="fa fa-table fa-3x"></i>
                </p>
                <p id="textOverviewCommonTableStructure" class="font-w600">Предпросмотр общей структуры шаблона</p>
            </div>
        </a>
    </div>
    <div class="col-9 col-md-6 col-xl-6">
        <a class="block block-link-shadow text-center" href="javascript::void(0)" name="overviewCommonArrayStructure" id="overviewCommonArrayStructure">
            <div class="block-content">
                <p class="mt-5">
                    <i class="fa fa-paint-brush fa-3x"></i>
                </p>
                <p id="textOverviewCommonArrayStructure" class="font-w600">Показать общий массив</p>
            </div>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="block block-rounded block-opt-refresh-icon8">
            <div class="block-header">
                <h3 class="block-title">Hierarchy Table Constructor</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="form-group">
                    <table class="table table-bordered" id="dynamic_head_actions">
                        <tr>
                            <td>
                                <button type="button" name="view_dynamic_head" id="view_dynamic_head" class="btn btn-success">Предпросмотр структуры шаблона</button>
                                <button type="button" name="sort_massive" id="sort_massive" class="btn btn-success">Показать массив</button>
                            </td>
                            <td>
                                Название отчета
                                <input type="text" id="reportName" name="reportName" placeholder="Введите название отчета" class="form-control name_list"/>
                            </td>
                            <td id="templateNameTd">
                                Шаблон
                                <select name="templateName" id="templateName" class="form-control name_list">
                                    <option value="-1">Выберите шаблон</option>
                                    <?php foreach($templates as $template):?>
                                        <option value="<?= $template->id; ?>"><?= $template->name; ?> </option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                            <td>
                                Только последние столбцы
                                <input type="checkbox" id="onlyLastFields" name="onlyLastFields" class="form-control" checked/>
                            </td>
                            <td>
                                Создавать таблицу?
                                <input type="checkbox" id="isCreateTable" name="isCreateTable" class="form-control"/>
                            </td>
                            <td id="tableNameTd" class="non">
                                Название таблицы
                                <input type="text" id="tableName" name="tableName" placeholder="Введите название" class="form-control name_list"/>
                            </td>
                        </tr>
                        <tr>
                            <td id="view_head" class="non">
                                таблица
                            </td>
                        </tr>
                        <tr>
                            <td id="view_massive_td" class="non">
                                <br>
                            </td>
                        </tr>
                    </table>
                    <form name="dynamic-insert" id="dynamic-insert" action="array.php" method="post">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dynamic">
                                <tr>
                                    <td>Идентификатор</td>
                                    <td>Родитель</td>
                                    <td>Сортировка на уровне</td>
                                    <td>Название поля в таблице</td>
                                    <td>Название для шапки</td>
                                    <td>Тип поля</td>
                                    <td>Длина поля</td>
                                    <td>Действия</td>
                                </tr>
                                <tr>
                                    <td><input type="text" name="id[]" placeholder="Идентификатор" class="form-control name_list" value="1"/></td>
                                    <td><input type="text" name="internal_key[]" placeholder="Родитель" class="form-control name_list" /></td>
                                    <td><input type="text" name="sort_order[]" placeholder="Сортировка на уровне" class="form-control name_list" /></td>
                                    <td><input type="text" name="field_name[]" placeholder="Название поля в таблице" class="form-control name_list" /></td>
                                    <td><input type="text" name="name[]" placeholder="Название для шапки" class="form-control name_list" /></td>
                                    <td><select name="field_type[]" class="form-control name_list">
                                            <option value="-1">Тип поля </option>
                                            <option value="varchar">Техт</option>
                                            <option value="int">Целочисленный</option>
                                        </select></td>
                                    <td><input type="text" name="lenght[]" placeholder="Длина поля" class="form-control name_list" /></td>
                                    <td><button type="button" name="add" id="add" class="btn btn-success">Добавить поле</button></td>
                                </tr>
                            </table>
                            <input type="button" id="createReport" class="btn btn-info" value="Создать отчет">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var i = 1; // счетчики полей
        var x = 1;

        $('#add').click(function(){ // при нажатии на кнопку ДОБАВИТЬ ПОЛЕ
            x++; // inc(x)
            $('#dynamic').append( // добавляем еще поля в таблице
                '<tr id="row'+x+'">' +
                '<td><input type="text" name="id[]" placeholder="Идентификатор" class="form-control name_list" value="'+x+'"/></td> ' +
                '<td><input type="text" name="internal_key[]" placeholder="Родитель" class="form-control name_list" /></td> ' +
                '<td><input type="text" name="sort_order[]" placeholder="Сортировка на уровне" class="form-control name_list" /></td> ' +
                '<td><input type="text" name="field_name[]" placeholder="Название поля в таблице" class="form-control name_list" /></td> ' +
                '<td><input type="text" name="name[]" placeholder="Название для шапки" class="form-control name_list" /></td> ' +
                '<td>' +
                '<select name="field_type[]" class="form-control name_list"> ' +
                '<option value="-1">Тип поля </option> ' +
                '<option value="varchar">Текст</option> ' +
                '<option value="int">Целочисленный </option> ' +
                '</select>' +
                '</td>' +
                '<td><input type="text" name="lenght[]" placeholder="Длина поля" class="form-control name_list" /></td>' +
                '<td>' +
                '<button type="button" name="remove_insert" id="'+x+'" class="btn btn-danger btn_remove_insert">X</button>' +
                '</td>' +
                '</tr>');
        });

        $(document).on('click', '.btn_remove_insert', function(){
            var button_id = $(this).attr("id");
            $('#row'+button_id+'').remove();
            x = x - 1;
        });

        $('#isCreateTable').click(function(){
            $('#tableNameTd').slideToggle('fast');
            $('#tableNameTd').focus();
        });

        $('#createReport').click(function(){
            if($('#tblName').val() != ''){
                $.ajax({
                    type: 'post',
                    url: '/table/create',
                    data: {
                        tableBody: $('#dynamic-insert').serializeObject(),
                        tableName: $('#tableName').val()
                    },
                    beforeSend: function () {
                        $("#createTable").text('Loading...Please, wait...');
                    },
                    success: function (response) {
                        $("#view_massive_td").empty();
                        $("#view_massive_td" ).removeClass( "non" );
                        $("#view_massive_td").html(response);
                    },
                    error: function () {
                        alert('Not OKay');
                    }
                });
            } else {
                alert('All fields are required!');
            }
        });

        $('#sort_massive').click(function(){
            if($("#sort_massive" ).text() === 'Показать массив') {
                $.ajax({
                    type: 'post',
                    url: '/table/overview-hierarchy-array',
                    data: $('#dynamic-insert').serialize(),
                    //dataType: "html",
                    beforeSend: function () {
                        $("#sort_massive").prop('disabled', true);
                        $("#sort_massive").text('Loading...Please, wait...');
                    },
                    success: function (response) {
                        $("#view_massive_td").empty();
                        $("#view_massive_td").html(response);
                        $("#sort_massive").text('Скрыть массив');
                        $("#sort_massive" ).removeClass( "btn-success" ).addClass( "btn-danger" );
                        $("#view_massive_td" ).removeClass( "non" );
                        $("#sort_massive").removeAttr('disabled');
                    },
                    error: function () {
                        alert('Not OKay');
                        $("#sort_massive").removeAttr('disabled');
                        $("#view_massive_td" ).addClass( "non" );
                        $("#sort_massive").text('Показать массив');
                        $("#sort_massive" ).addClass( "btn-success" ).removeClass( "btn-danger" );
                        return false;
                    }
                });
            } else {
                $("#view_massive_td" ).addClass( "non" );
                $("#sort_massive").text('Показать массив');
                $("#sort_massive" ).addClass( "btn-success" ).removeClass( "btn-danger" );
            }
        });

        $('#view_dynamic_head').click(function(){
            if($("#view_dynamic_head" ).text() === 'Предпросмотр структуры шаблона'){
                $.ajax({
                    type: 'post',
                    url: '/table/overview-hierarchy',
                    data: $('#dynamic-insert').serialize(),
                    beforeSend: function () {
                        $("#view_dynamic_head").prop('disabled', true);
                        $("#view_dynamic_head").text('Loading...Please, wait...');
                    },
                    success: function (response) {
                        $("#view_head").empty();
                        $("#view_head").html('<table class="">'+response+'</table>');
                        $("#view_head" ).removeClass( "non" );
                        $("#view_dynamic_head" ).removeClass( "btn-success" ).addClass( "btn-danger" );
                        $("#view_dynamic_head" ).text( "Скрыть предпросмотр структуры шаблона" );
                        $("#view_dynamic_head").removeAttr('disabled');
                    },
                    error: function () {
                        alert('Not OKay');
                        $("#view_dynamic_head").removeAttr('disabled');
                        $("#view_head" ).addClass( "non" );
                        $("#view_dynamic_head" ).addClass( "btn-success" ).removeClass( "btn-danger" );
                        $("#view_dynamic_head" ).text( "Предпросмотр структуры шаблона" );
                        return false;
                    }
                });
            } else {
                $("#view_head" ).addClass( "non" );
                $("#view_dynamic_head" ).addClass( "btn-success" ).removeClass( "btn-danger" );
                $("#view_dynamic_head" ).text( "Предпросмотр структуры шаблона" );
            }

        });
    });
</script>