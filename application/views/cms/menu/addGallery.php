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
            get_galls($(this).val());
        });

        function get_galls(val) {
            $.get("<?php echo base_url('cms/menu/listGallereis')?>/" + val,
                function (data) {
                    $('#gallereis').html(data);
                });
        }

        get_galls($('#name').val());

    });
</script>

<?php
echo validation_errors('<span class="error">', '</span>');
echo form_open('cms/menu/addGallery', "id=\"form\" class=\"form-horizontal form-dialog \"");
echo form_hidden('pmenu', $pmenu)
?>

<div class="control-group">
    <label for="name" class="control-label">Название галереи</label>
    <?php
    echo form_input('name', set_value('name') != null ? set_value('name') : null, "id=\"name\" class=\"input-xlarge\"");
    ?>
</div>

<div class="pages" id="gallereis"></div>

<?php echo form_close(); ?>
