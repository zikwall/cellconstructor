<style>
    #my-code-wrapper {
        width:350px;
        height:250px;
    }
</style>

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
    <div class="col-9 col-md-6 col-xl-6">
        <a class="block block-link-shadow text-center" href="javascript::void(0)" name="hierarchyUp" id="hierarchyUp" onclick="jQuery('#modal-up').modal('toggle');">
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-magnifier fa-3x"></i>
                </p>
                <p id="textHierarchyUp" class="font-w600">Редактор вверхней шапки</p>
            </div>
        </a>
    </div>
    <div class="col-9 col-md-6 col-xl-6">
        <a class="block block-link-shadow text-center" href="javascript::void(0)" name="hierarchyLeft" id="hierarchyLeft" onclick="jQuery('#modal-left').modal('toggle')";>
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-bar-chart fa-3x"></i>
                </p>
                <p id="textHierarchyLeft" class="font-w600">Редактор боковой шапки</p>
            </div>
        </a>
    </div>
</div>
<div class="block block-rounded block-opt-refresh-icon8">
    <div class="block-header">
        <h3 class="block-title">Hierarchy Table Constructor</h3>
    </div>
    <div class="block-content block-content-full">
        <div class="form-group">
            <table class="table table-bordered" id="dynamic_head_actions">
                <tr>
                    <td id="overviewCommonTableStructureContainer" class="">
                        таблица
                    </td>
                </tr>
                <tr>
                    <td id="overviewCommonArrayStructureContainer" class="">

                    </td>
                </tr>
            </table>
        </div>
        <form>
            <input type="text" id="templateName" name="templateName" class="form-group form-control" placeholder="Название шаблона">
            <input type="button" id="createMatrixTemplate" class="btn btn-info" value="Создать шаблон">
            <input type="button" id="updateMatrixTemplate" class="btn btn-success" value="Обновить шаблон">
        </form>
        <div class="col-md-12" id="createMatrixTemplateResult">

        </div>
    </div>
</div>

<div class="modal" id="modal-up" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Up Hierarchy Construct</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div id="cloneUp">
                        <form name="up" id="up" class="" action="array.php" method="post">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dynamic_up_head_actions">
                                    <tr>
                                        <td id="overviewUpTableStructureContainer" class="non">
                                            таблица
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="overviewUpArrayStructureContainer" class="non">
                                            <br>
                                        </td>
                                    </tr>
                                </table>
                                <table class="table table-bordered" id="dynamic_up">
                                    <tr>
                                        <td>
                                            <button type="button" name="overviewUpTableStructure" id="overviewUpTableStructure" class="btn btn-success">Предпросмотр структуры шаблона</button>
                                        </td>
                                        <td>
                                            <button type="button" name="overviewUpArrayStructure" id="overviewUpArrayStructure" class="btn btn-success">Показать массив</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Идентификатор</td>
                                        <td>Родитель</td>
                                        <td>Сортировка на уровне</td>
                                        <td>Название для шапки</td>
                                        <td>Тип поля</td>
                                        <td>Валидация</td>
                                        <td>Действия</td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="id[]" placeholder="Идентификатор" class="form-control name_list" value="1"/></td>
                                        <td><input type="text" name="internal_key[]" placeholder="Родитель" class="form-control name_list" /></td>
                                        <td><input type="text" name="sort_order[]" placeholder="Сортировка на уровне" class="form-control name_list" /></td>
                                        <td><input type="text" name="name[]" placeholder="Название для шапки" class="form-control name_list" /></td>
                                        <td><select name="field_type[]" class="form-control name_list">
                                                <option value="safe">Безопасный аттрибут </option>
                                                <option value="varchar">Техт</option>
                                                <option value="int">Целочисленный</option>
                                                <option value="date">Дата</option>
                                                <option value="safe">Длинный текст</option>
                                            </select></td>
                                            <input type="hidden" id="validate_1" name="validate[]" placeholder="Валидация" class="form-control name_list" />
                                        <td>
                                            <button type="button" name="v_func" data-func="1" class="btn btn-success add_validate_function">V</button>
                                        </td>
                                        <td>
                                            <button type="button" name="add" id="up_add" class="btn btn-success">Добавить поле</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-success" data-dismiss="modal">
                    <i class="fa fa-check"></i> Perfect
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal col-md-12" id="modal-left" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Left Hierarchy Construct</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div id="cloneLeft">
                        <form name="left" id="left" class="" action="array.php" method="post">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dynamic_left_head_actions">
                                    <tr>
                                        <td id="overviewLeftTableStructureContainer" class="non">
                                            таблица
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="overviewLeftArrayStructureContainer" class="non">
                                            <br>
                                        </td>
                                    </tr>
                                </table>
                                <table class="table table-bordered" id="dynamic_left">
                                    <tr>
                                        <td>
                                            <button type="button" name="overviewLeftTableStructure" id="overviewLeftTableStructure" class="btn btn-success">Предпросмотр структуры шаблона</button>
                                        </td>
                                        <td>
                                            <button type="button" name="soverviewLeftArrayStructure" id="soverviewLeftArrayStructure" class="btn btn-success">Показать массив</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Идентификатор</td>
                                        <td>Родитель</td>
                                        <td>Сортировка на уровне</td>
                                        <td>Название для шапки</td>
                                        <td>Действия</td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="id[]" placeholder="Идентификатор" class="form-control name_list" value="1"/></td>
                                        <td><input type="text" name="internal_key[]" placeholder="Родитель" class="form-control name_list" value="0"/></td>
                                        <td><input type="text" name="sort_order[]" placeholder="Сортировка на уровне" class="form-control name_list" /></td>
                                        <td>
                                            <select name="heading[]" class="form-control name_list">
                                                <option selected value="1">Нет</option>
                                                <option value="0">Да</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="name[]" placeholder="Название для шапки" class="form-control name_list" /></td>
                                        <td><button type="button" name="add" id="left_add" class="btn btn-success">Добавить поле</button></td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-success" data-dismiss="modal">
                    <i class="fa fa-check"></i> Perfect
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var i = 1;
        var x = 1;

        $('#isCreateReport').click(function(){
            $('#reportNameTd').slideToggle('fast');
            $('#reportNameTd').focus();
        });

        $('#isCreateTable').click(function(){
            $('#tableNameTd').slideToggle('fast');
            $('#tableNameTd').focus();
        });

        $('#up_add').click(function(){
            x++; // inc(x)
            $('#dynamic_up').append(
                '<tr id="row'+x+'">' +
                '<td><input type="text" name="id[]" placeholder="Идентификатор" class="form-control name_list" value="'+x+'"/></td> ' +
                '<td><input type="text" name="internal_key[]" placeholder="Родитель" class="form-control name_list" /></td> ' +
                '<td><input type="text" name="sort_order[]" placeholder="Сортировка на уровне" class="form-control name_list" /></td> ' +
                '<td><input type="text" name="name[]" placeholder="Название для шапки" class="form-control name_list" /></td> ' +
                '<td>' +
                '<select name="field_type[]" class="form-control name_list"> ' +
                '<option value="safe">Безопасный аттрибут </option> ' +
                '<option value="varchar">Текст</option> ' +
                '<option value="int">Целочисленный </option> ' +
                '<option value="date">Дата </option> ' +
                '<option value="safe">Длинный текст </option> ' +
                '</select>' +
                '</td>' +
                '<input type="hidden" id="validate_'+x+'" name="validate[]" placeholder="Валидация" class="form-control name_list" />' +
                '<td>' +
                '<button type="button" name="v_func" data-func="'+x+'" class="btn btn-success add_validate_function">V</button>' +
                '</td>' +
                '<td>' +
                '<button type="button" name="remove_insert" id="'+x+'" class="btn btn-danger btn_remove_insert">X</button>' +
                '</td>' +
                '</tr>');
        });

        $('#left_add').click(function(){
            i++; // inc(x)
            $('#dynamic_left').append(
                '<tr id="row'+i+'">' +
                '<td><input type="text" name="id[]" placeholder="Идентификатор" class="form-control name_list" value="'+i+'"/></td> ' +
                '<td><input type="text" name="internal_key[]" placeholder="Родитель" class="form-control name_list" /></td> ' +
                '<td><input type="text" name="sort_order[]" placeholder="Сортировка на уровне" class="form-control name_list" /></td> ' +
                '<td><select name="heading[]" class="form-control name_list"> ' +
                '<option selected value="1">Нет</option> ' +
                '<option value="0">Да</option> ' +
                '</select></td>' +
                '<td><input type="text" name="name[]" placeholder="Название для шапки" class="form-control name_list" /></td> ' +
                '<td>' +
                '<button type="button" name="remove_insert" id="'+i+'" class="btn btn-danger btn_remove_insert">X</button>' +
                '</td>' +
                '</tr>');
        });

        $(document).on('click', '.add_validate_function', function () {
            var $element_id = $(this).attr('data-func');

            $.confirm({
                title: 'Add validate function',
                columnClass: 'col-md-12',
                onContentReady: function () {
                    var flask = new CodeFlask;
                    flask.run('#my-code-wrapper', { language: 'javascript', lineNumbers: true });
                },
                content: ''+
               '<label>Enter something here</label>' +
               '<div id="my-code-wrapper"></div>'
            })
        });

        $(document).on('click', '.btn_remove_insert', function(){
            var button_id = $(this).attr("id");
            $('#row'+button_id+'').remove();
            x = x - 1;
        });

        $('#createMatrixTemplate').click(function(){
            var btnSelector = '#createMatrixTemplate';
            var tdSelector = '#createMatrixTemplateResult';
            if($('#templateName').val() != ''){
                $.ajax({
                    type: 'POST',
                    url: '/template/create/matrix',
                    data: {
                        isCreateReport: $('#isCreateReport').val(),
                        isCreateTable: $('#isCreateTable').val(),
                        reportName: $('#reportName').val(),
                        templateName: $('#templateName').val(),
                        tableName: $('#tableName').val(),
                        up: $('#up').serializeObject(),
                        left: $('#left').serializeObject()
                    },
                    beforeSend: function () {
                        $(btnSelector).text('Loading...Please, wait...');
                    },
                    success: function (response) {
                        $(tdSelector).empty();
                        $(tdSelector).html(response);
                        $(tdSelector).removeClass( "non" );
                    },
                    error: function () {
                        alert('Not OKay');
                        $(tdSelector).addClass( "non" );
                        return false;
                    }
                });
            } else {
                alert('Введите название шаблона')
            }
        });

        $('#overviewCommonArrayStructure').click(function(){
            var btnSelector = '#textOverviewCommonArrayStructure';
            var tdSelector = '#overviewCommonArrayStructureContainer';
            if($(btnSelector).text() === 'Показать общий массив') {
                $.ajax({
                    type: 'POST',
                    url: '/table/post',
                    data: {
                        up: $('#up').serializeObject(),
                        left: $('#left').serializeObject()
                    },
                    beforeSend: function () {
                        $(btnSelector).text('Loading...Please, wait...');
                    },
                    success: function (response) {
                        $(tdSelector).empty();
                        $(tdSelector).html(response);
                        $(btnSelector).text('Скрыть массив');
                        $(tdSelector).removeClass( "non" );
                    },
                    error: function () {
                        alert('Not OKay');
                        $(tdSelector).addClass( "non" );
                        $(btnSelector).text('Показать общий массив');
                        return false;
                    }
                });
            } else {
                $(tdSelector).addClass( "non" );
                $(btnSelector).text('Показать общий массив');
            }
        });

        $('#overviewCommonTableStructure').click(function(){
            var btnSelector = '#textOverviewCommonTableStructure';
            var tdSelector = '#overviewCommonTableStructureContainer';
            if($(btnSelector).text() === 'Предпросмотр общей структуры шаблона') {
                $.ajax({
                    type: 'POST',
                    url: '/table/overview-matrix',
                    data: {
                        up: $('#up').serializeObject(),
                        left: $('#left').serializeObject()
                    },
                    beforeSend: function () {
                        $(btnSelector).text('Loading...Please, wait...');
                    },
                    success: function (response) {
                        $(tdSelector).empty();
                        $(tdSelector).empty();
                        $(tdSelector).html(response);
                        $(btnSelector).text('Скрыть предпросмотр общей структуры шаблона');
                        $(tdSelector).removeClass("non");
                    },
                    error: function () {
                        alert('Not OKay');
                        $(btnSelector).text("Предпросмотр общей структуры шаблона");
                        return false;
                    }
                });
            } else {
                $(tdSelector).addClass( "non" );
                $(btnSelector).text('Предпросмотр общей структуры шаблона');
            }
        });

        $('#overviewUpArrayStructure').click(function(){
            var btnSelector = '#overviewUpArrayStructure';
            var tdSelector = '#overviewUpArrayStructureContainer';

            if($(btnSelector).text() === 'Показать массив') {
                $.ajax({
                    type: 'post',
                    url: '/table/post-array/2',
                    data: $('#up').serialize(),
                    //dataType: "html",
                    beforeSend: function () {
                        $(btnSelector).prop('disabled', true);
                        $(btnSelector).text('Loading...Please, wait...');
                    },
                    success: function (response) {
                        $(tdSelector).empty();
                        $(tdSelector).html(response);
                        $(btnSelector).text('Скрыть массив');
                        $(btnSelector).removeClass( "btn-success" ).addClass( "btn-danger" );
                        $(tdSelector).removeClass( "non" );
                        $(btnSelector).removeAttr('disabled');
                    },
                    error: function () {
                        alert('Not OKay');
                        $(btnSelector).removeAttr('disabled');
                        $(tdSelector).addClass( "non" );
                        $(btnSelector).text('Показать массив');
                        $(btnSelector).addClass( "btn-success" ).removeClass( "btn-danger" );
                        return false;
                    }
                });
            } else {
                $(tdSelector).addClass( "non" );
                $(btnSelector).text('Показать массив');
                $(btnSelector).addClass( "btn-success" ).removeClass( "btn-danger" );
            }
        });

        $('#overviewUpTableStructure').click(function(){
            var btnSelector = '#overviewUpTableStructure';
            var tdSelector = '#overviewUpTableStructureContainer';

            if($(btnSelector).text() === 'Предпросмотр структуры шаблона'){
                $.ajax({
                    type: 'post',
                    url: '/table/overview-hierarchy',
                    data: $('#up').serialize(),
                    beforeSend: function () {
                        $(btnSelector).prop('disabled', true);
                        $(btnSelector).text('Loading...Please, wait...');
                    },
                    success: function (response) {
                        $(tdSelector).empty();
                        $(tdSelector).html('<table class="">'+response+'</table>');
                        $(tdSelector).removeClass( "non" );
                        $(btnSelector).removeClass( "btn-success" ).addClass( "btn-danger" );
                        $(btnSelector).text( "Скрыть предпросмотр структуры шаблона" );
                        $(btnSelector).removeAttr('disabled');
                    },
                    error: function () {
                        alert('Not OKay');
                        $(btnSelector).removeAttr('disabled');
                        $(tdSelector).addClass( "non" );
                        $(btnSelector).addClass( "btn-success" ).removeClass( "btn-danger" );
                        $(btnSelector).text( "Предпросмотр структуры шаблона" );
                        return false;
                    }
                });
            } else {
                $(tdSelector).addClass( "non" );
                $(btnSelector).addClass( "btn-success" ).removeClass( "btn-danger" );
                $(btnSelector).text( "Предпросмотр структуры шаблона" );
            }

        });

        $('#overviewLeftArrayStructure').click(function(){
            var btnSelector = '#overviewLeftArrayStructure';
            var tdSelector = '#overviewLeftArrayStructureContainer';

            if($(btnSelector).text() === 'Показать массив') {
                $.ajax({
                    type: 'post',
                    url: '/table/post-array/2',
                    data: $('#left').serialize(),
                    //dataType: "html",
                    beforeSend: function () {
                        $(btnSelector).prop('disabled', true);
                        $(btnSelector).text('Loading...Please, wait...');
                    },
                    success: function (response) {
                        $(tdSelector).empty();
                        $(tdSelector).html(response);
                        $(btnSelector).text('Скрыть массив');
                        $(btnSelector).removeClass( "btn-success" ).addClass( "btn-danger" );
                        $(tdSelector).removeClass( "non" );
                        $(btnSelector).removeAttr('disabled');
                    },
                    error: function () {
                        alert('Not OKay');
                        $(btnSelector).removeAttr('disabled');
                        $(tdSelector).addClass( "non" );
                        $(btnSelector).text('Показать массив');
                        $(btnSelector).addClass( "btn-success" ).removeClass( "btn-danger" );
                        return false;
                    }
                });
            } else {
                $(tdSelector).addClass( "non" );
                $(btnSelector).text('Показать массив');
                $(btnSelector).addClass( "btn-success" ).removeClass( "btn-danger" );
            }
        });

        $('#overviewLeftTableStructure').click(function(){
            var btnSelector = '#overviewLeftTableStructure';
            var tdSelector = '#overviewLeftTableStructureContainer';

            if($(btnSelector).text() === 'Предпросмотр структуры шаблона'){
                $.ajax({
                    type: 'post',
                    url: '/table/overview-hierarchy-rows',
                    data: $('#left').serialize(),
                    beforeSend: function () {
                        $(btnSelector).prop('disabled', true);
                        $(btnSelector).text('Loading...Please, wait...');
                    },
                    success: function (response) {
                        $(tdSelector).empty();
                        $(tdSelector).html('<table class="">'+response+'</table>');
                        $(tdSelector).removeClass( "non" );
                        $(btnSelector).removeClass( "btn-success" ).addClass( "btn-danger" );
                        $(btnSelector).text( "Скрыть предпросмотр структуры шаблона" );
                        $(btnSelector).removeAttr('disabled');
                    },
                    error: function () {
                        alert('Not OKay');
                        $(btnSelector).removeAttr('disabled');
                        $(tdSelector).addClass( "non" );
                        $(btnSelector).addClass( "btn-success" ).removeClass( "btn-danger" );
                        $(btnSelector).text( "Предпросмотр структуры шаблона" );
                        return false;
                    }
                });
            } else {
                $(tdSelector).addClass( "non" );
                $(btnSelector).addClass( "btn-success" ).removeClass( "btn-danger" );
                $(btnSelector).text( "Предпросмотр структуры шаблона" );
            }

        });
    });
</script>