<script type="text/javascript">

    $(document).ready(function () {

        $("#date").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, changeMonth: true},
            $.datepicker.regional['ru']);

        $("#form").validate({
            errorElement: 'span',
            errorClass: 'error-block',
            submitHandler: function (form) {
                $(form).ajaxSubmit(
                    {
                        dataType: "json",
                        success: function (data) {
                            $("#progress_dialog").dialog('close');
                            $("#treeDialog").dialog("close");
                            var node = $("#tree").dynatree("getActiveNode");
                            node.addChild(data);
                        },

                        beforeSubmit: function () {
                            $("#progress_dialog").dialog('open');
                            return true;
                        }
                    });
            },
            rules: {
                uri: {
                    required: true,
                    maxlength: 255
                },
                sName: {
                    maxlength: 255
                },
                count_element: {
                    digits: true
                }
            }
        });

        $("#type").change(function (e) {
            if ($(this).val() == 'abs')
                $('#uri').attr('data-rule-url', 'true');
            else
                $('#uri').removeAttr('data-rule-url');
        });


        $("#myTab a").click(function (e) {
            e.preventDefault();
            $(this).tab("show");
        });

        $("#myTab a:first").tab("show");

    });

</script>
<?php
echo validation_errors('<span class="error">', '</span>');
echo form_open('cms/menu/addMenu', "id=\"form\" class=\"form-horizontal form-dialog \"");
echo form_hidden('pmenu', $pmenu->id)
?>

<div class="control-group">
	<span class="label label-info">Родительское меню : "<?= isset($pmenu->head) ? $pmenu->head : $pmenu->name ?>"
	</span>
</div>

<ul class="nav nav-tabs" id="myTab">
    <?php foreach ($langs as $lang): ?>
        <li><a href="#tab<?= $lang->id ?>" data-toggle="tab<?= $lang->id ?>"><?= $lang->text ?></a></li>
    <?php endforeach ?>
</ul>

<div class="tab-content">
    <?php $x = 0;
    foreach ($langs as $lang) : ?>
        <?php $f_head = sprintf('data[%s][head]', $lang->id);
        $attr = 'data-rule-required="true" data-rule-maxlength="255"';
        ?>
        <div class="tab-pane" id="tab<?= $lang->id ?>">
            <div class="control-group">
                <label for="<?= $f_head ?>" class="control-label">Название</label>
                <?= form_input($f_head, set_value($f_head), "id=\"$f_head\" " . ($x == 0 ? $attr : null) . 'class="input-xlarge"'); ?>
            </div>
        </div>
        <?php $x++; endforeach ?>
</div>

<div class="control-group">
    <label for="type" class="control-label">Тип ссылки</label>
    <?= form_dropdown('type', ['rel' => 'В пределах сайта', 'abs' => 'Внешний ресурс (URL)'], set_value('type'), 'style="width:284px;" class="" id="type"'); ?>
</div>

<div class="control-group">
    <label for="uri" class="control-label"><abbr title="Все ссылки формируются относительно родительского меню, для обнуления используейте &#171;/&#187; (слеш)">Ссылка</abbr></label>
    <?= form_input('uri', set_value('uri'), 'class="input-xlarge" id="uri"'); ?>
</div>

<div class="control-group">
    <label for="template" class="control-label"><abbr title="Обработчик маршрута, см. пункт меню &#171;Шаблоны страниц&#187;">Шаблон</abbr></label>
    <?= form_dropdown('template', $templates, set_value('template'), 'style="width:284px;" class="" id="template"'); ?>
</div>

<div class="control-group">
    <label for="sName" class="control-label"><abbr title="Используется CMS (не следует редактировать без необходимости)">Служебное название</abbr></label>
    <?= form_input('sName', set_value('sName'), 'id="sName" class="input-xlarge"'); ?>
</div>

<div class="control-group">
    <label for="template" class="control-label">Сортировать по</label>
    <?= form_dropdown('sort', ['priority' => 'По умолчанию', 'name' => 'Название', 'date' => 'Дата'], set_value('sort'), 'style="width:284px;" class="" id="sort"'); ?>
</div>

<div class="control-group">
    <label for="count_element" class="control-label"><abbr title="Кол-во элементов (меню), которые будут показаны на странице (требует специальной обработки в шаблоне)">Кол-во элементов</abbr></label>
    <?= form_input('count_elem', set_value('count_elem'), 'id="count_element" class="input-medium"'); ?>
</div>

<div class="control-group">
    <label for="date" class="control-label">Дата</label>
    <?= form_input('date', set_value('date'), 'id="date" class="input-medium"'); ?>
</div>

<div class="control">
    <label class="checkbox" for="isService"><abbr title="Если флажок установлен, данное меню НЕ будет отображаться на сайте (при этом обработка маршрута будет продолжаться)">Служебное меню (скрыть на сайте)</abbr> <?= form_checkbox('isService', set_value('isService') != null ? set_value('isService') : 1, set_value('isService'), 'id=isService');
        ?>
    </label>
</div>

<?= form_close(); ?>