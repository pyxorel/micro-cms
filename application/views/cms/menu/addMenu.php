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
                            node.addChild(data);
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
		
		$("#type").change(function (e) {
			if($(this).val()=='abs')
				$('#uri').attr('data-rule-url','true');
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
echo form_open('cms/menu/addMenu', "id=\"form\" class=\"form-horizontal well form-dialog \"");
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
    <?php $x=0;foreach ($langs as $lang) : ?>
        <?php $f_head = sprintf('data[%s][head]', $lang->id);
			$attr = 'data-rule-required="true" data-rule-maxlength="255"';
		?>
        <div class="tab-pane" id="tab<?= $lang->id ?>">
            <div class="control-group">
                <label for="<?=$f_head?>" class="control-label">Название</label>
                <?= form_input($f_head, set_value($f_head), "id=\"$f_head\" " . ($x==0 ? $attr : null) . 'class="input-xlarge"'); ?>
            </div>
        </div>
    <?php $x++; endforeach ?>
</div>

<div class="control-group">
    <label for="type" class="control-label">Тип ссылки</label>
    <?= form_dropdown('type', ['rel'=>'В пределах сайта', 'abs'=>'Внешний ресурс (URL)'],  set_value('type'), 'style="width:284px;" class="" id="type"'); ?>
</div>

<div class="control-group">
    <label for="uri" class="control-label">Ссылка</label>
    <?= form_input('uri', set_value('uri'), 'class="input-xlarge" id="uri"');  ?>
</div>

<div class="control-group">
    <label for="template" class="control-label">Шаблон</label>
    <?= form_dropdown('template', $templates,  set_value('template'), 'style="width:284px;" class="" id="template"'); ?>
</div>

<div class="control-group">
    <label for="sName" class="control-label"><abbr title="Используется CMS (не слудет редактировать без необходимости)">Служебное название</abbr></label>
    <?= form_input('sName', set_value('sName'), 'id="sName" class="input-xlarge"');?>
</div>

<div class="control">
    <label class="checkbox" for="isService"> <?=form_checkbox('isService', set_value('isService') != null ? set_value('isService') : 1, set_value('isService'), 'id=isService');
        ?> Служебное меню
    </label>
</div>

<?= form_close(); ?>