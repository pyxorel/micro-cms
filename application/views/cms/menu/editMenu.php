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
                            node.data.title = data.title;
                            node.data.key = data.key;
                            node.render();
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

        $("#myTab a").click(function (e) {
            e.preventDefault();
            $(this).tab("show");
        });

        $("#myTab a:first").tab("show");

        $('#goto_template').click(function(){
            $(this).attr('href', '<?= base_url('cms/template/editView')?>' + '/' + $('#_template').val());
        });

    });

</script>
<?php
echo validation_errors('<span class="error">', '</span>');
echo form_open('cms/menu/editMenu', "id=\"form\" class=\"form-horizontal form-dialog \"");
echo form_hidden('menu', $menu[DEFAULT_LANG_CODE]->id)
?>

<ul class="nav nav-tabs" id="myTab">
    <?php foreach ($langs as $lang): ?>
        <li><a href="#tab<?= $lang->id ?>" data-toggle="tab<?= $lang->id ?>"><?= $lang->text ?></a></li>
    <?php endforeach ?>
</ul>

<div class="tab-content">
    <?php foreach ($langs as $lang) : ?>
        <?php $f_head = sprintf('data[%s][head]', $lang->id) ?>

        <div class="tab-pane" id="tab<?= $lang->id ?>">
            <?php if (array_key_exists($lang->code, $menu)) : ?>
                <div class="control-group">
                    <label for="head" class="control-label">Название</label>
                    <?= form_input($f_head, isset($menu) ? $menu[$lang->code]->head : set_value('head'), 'class="input-xlarge" data-rule-required="true" data-rule-maxlength="255"'); ?>
                </div>
            <?php else: ?>
            <?php endif ?>
        </div>
    <?php endforeach ?>
</div>

<?php
$type = empty($menu[DEFAULT_LANG_CODE]->url) ? 'rel' : 'abs';
$uri = !empty($menu[DEFAULT_LANG_CODE]->url) ? $menu[DEFAULT_LANG_CODE]->url : $menu[DEFAULT_LANG_CODE]->name;
?>

<div class="control-group">
    <label for="type" class="control-label">Тип ссылки</label>
    <?= form_dropdown('type', ['rel' => 'В пределах сайта', 'abs' => 'Внешний ресурс (URL)'], $type, 'style="width:284px;" class="" id="type"'); ?>
</div>

<div class="control-group">
    <label for="uri" class="control-label"><abbr title="Все ссылки формируются относительно родительского меню, для обнуления используйте &#171;/&#187; (слеш)">Ссылка</abbr></label>
    <?= form_input('uri', $uri, 'id="uri" class="input-xlarge"'); ?>
</div>

<div class="control-group">
    <label for="template" class="control-label"><abbr title="Обработчик маршрута, см. пункт меню &#171;Шаблоны страниц&#187;">Шаблон</abbr></label>
    <?= form_dropdown('template', $templates, isset($menu) ? $menu[DEFAULT_LANG_CODE]->template : set_value('template'), 'style="width:284px;" class="" id="_template"'); ?>
    <span><a href="#" id="goto_template" target="_blank" title="Перейти">>>></a></span>
</div>

<div class="control-group">
    <label for="sName" class="control-label"><abbr title="Используется CMS (не следует редактировать без необходимости)">Служебное название</abbr></label>
    <?= form_input('sName', isset($menu) ? $menu[DEFAULT_LANG_CODE]->service_name : set_value('sName'), 'id="sName" class="input-xlarge"'); ?>
</div>

<div class="control-group">
    <label for="sort" class="control-label">Сортировать по</label>
    <?= form_dropdown('sort', ['priority' => 'По умолчанию', 'name' => 'Название', 'date' => 'Дата'], isset($menu) ? $menu[DEFAULT_LANG_CODE]->sort : set_value('sort'), 'style="width:284px;" class="" id="sort"'); ?>
</div>

<div class="control-group">
    <label for="count_element" class="control-label"><abbr title="Кол-во элементов (меню), которые будут показаны на странице (требует специальной обработки в шаблоне)">Кол-во элементов</abbr></label>
    <?= form_input('count_elem', isset($menu) ? $menu[DEFAULT_LANG_CODE]->count_elem : set_value('count_element'), 'id="count_element" class="input-medium"'); ?>
</div>

<div class="control-group">
    <label for="date" class="control-label">Дата</label>
    <?= form_input('date', isset($menu) ? $menu[DEFAULT_LANG_CODE]->date : set_value('date'), 'id="date" class="input-medium"'); ?>
</div>

<div class="control">
    <label for="isService" class="checkbox"><abbr title="Если флажок установлен, данное меню НЕ будет отображаться на сайте (при этом обработка маршрута будет продолжаться)">Служебное меню (скрыть на сайте)</abbr> <?=
        form_checkbox('isService', 1, (isset($menu) && $menu[DEFAULT_LANG_CODE]->is_service) ? true : set_value('isService'), 'id="isService"');
        ?>
    </label>
</div>


<?= form_close(); ?>
