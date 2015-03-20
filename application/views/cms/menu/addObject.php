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
            clearTimeout($.data(this, 'timer'));
            var wait = setTimeout(function () {
                get_objs($('#name').val())
            }, 700);
            $(this).data('timer', wait);
        });

        function get_objs(val) {
            $("#progress_dialog").dialog('open');
            $.get("<?= base_url('cms/menu/listObjects')?>/" + <?= $pmenu?> +'/' + val,
                function (data) {
                    $('#pages').html(data);
                    $("#progress_dialog").dialog('close');
                });
        }

        setTimeout(function () {
            get_objs($('#name').val())
        }, 0);
    });
</script>

<?php
echo validation_errors('<span class="error">', '</span>');
echo form_open('cms/menu/addObject', "id=\"form\" class=\"form-horizontal form-dialog \"");
echo form_hidden('pmenu', $pmenu)
?>

<div class="control-group">
    <label for="name" class="control-label">Название</label>
    <?= form_input('name', set_value('name') != null ? set_value('name') : null, "id=\"name\" class=\"input-large\""); ?>
</div>

<div class="pages" id="pages"></div>

<?= form_close(); ?>
