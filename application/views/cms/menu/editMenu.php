<script type="text/javascript">
	
    $(document).ready(function () {
		
		$("#form").validate({
			errorElement:'span',
			errorClass:'error-block',
            submitHandler: function (form) {
                $(form).ajaxSubmit(
                    {
                        dataType: "json",
                        success: function (data) {
                            $("#treeDialog").dialog("close");
                            var node = $("#tree").dynatree("getActiveNode");
                            node.data.title = data.title;
                            node.data.key = data.key;
                            node.render();
                        } });
            },
            rules: {
                uri: {
					required: true,
                    maxlength: 255
                },
                sName: {
                    maxlength: 255
                }
            }
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
echo form_open('cms/menu/editMenu', "id=\"form\" class=\"form-horizontal well form-dialog \"");
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
$type = empty($menu[DEFAULT_LANG_CODE]->url) ? 'rel' : 'abs' ;
$uri =  !empty($menu[DEFAULT_LANG_CODE]->url) ? $menu[DEFAULT_LANG_CODE]->url : $menu[DEFAULT_LANG_CODE]->name;
?>

<div class="control-group">
    <label for="type" class="control-label">Тип ссылки</label>
    <?= form_dropdown('type', ['rel'=>'В пределах сайта', 'abs'=>'Внешний ресурс (URL)'], $type, 'style="width:284px;" class="" id="type"'); ?>
</div>

<div class="control-group">
    <label for="uri" class="control-label">Ссылка</label>
    <?= form_input('uri', $uri , 'id="uri" class="input-xlarge"');?>
</div>

<div class="control-group">
    <label for="template" class="control-label">Шаблон</label>
    <?= form_dropdown('template', $templates, isset($menu) ? $menu[DEFAULT_LANG_CODE]->template: set_value('template'), 'style="width:284px;" class="" id="template"'); ?>
</div>


<div class="control-group">
    <label for="sName" class="control-label"><abbr title="Используется CMS (не слудет редактировать без необходимости)">Служебное название</abbr></label>
    <?= form_input('sName', isset($menu) ? $menu[DEFAULT_LANG_CODE]->service_name : set_value('sName'), 'id="sName" class="input-xlarge"'); ?>
</div>

<div class="control">
    <label for="isService" class="checkbox"> <?=
        form_checkbox('isService', 1, (isset($menu) && $menu[DEFAULT_LANG_CODE]->is_service) ? true : set_value('isService'), 'id="isService"');
        ?> Служебное меню
    </label>
</div>


<?php echo form_close(); ?>
