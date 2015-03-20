<script type="text/javascript">
    $(function () {
        $("#form").validate({
            submitHandler: function (form) {
                $(form).ajaxSubmit(
                    {
                        success: function (data) {
                            $("#treeDialog").dialog("close");
                            try {
                                var obj = jQuery.parseJSON(data);
                                if (obj !== null) {
                                    var node = $("#tree").dynatree("getActiveNode");
                                    node.addChild(obj);
                                }
                            } catch (e) {
                                alert(data);
                            }
                        }
                    });
            }
        });

        $('#name').keyup(function (e) {
            if (e.which != 13) {
                get_pages($(this).val());
            }
        });

        function get_pages(val) {
            $.get("<?= base_url('cms/menu/listPages')?>/" + val,
                function (data) {
                    $('#pages').html(data);
                });
        }

        get_pages($('#name').val());

    });
</script>

<?php
echo validation_errors('<span class="error">', '</span>');
echo form_open('cms/menu/addPage', "id=\"form\" class=\"form-horizontal form-dialog \"");
echo form_hidden('pmenu', $pmenu)
?>

<div class="control-group">
    <label for="name" class="control-label">Название страницы</label>
    <?= form_input('name', set_value('name') != null ? set_value('name') : null, "id=\"name\" class=\"input-large\""); ?>
</div>

<div class="pages" id="pages"></div>

<?= form_close(); ?>
