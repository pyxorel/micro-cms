<body>

<?php include_once 'application/views/cms/menu.php'; ?>
<script src="<?= base_url('application/content/cms/javaScripts/dynatree/jquery/jquery.cookie.js') ?>"
        type="text/javascript" charset="utf-8"></script>
<script src="<?= base_url('application/content/cms/javaScripts/dynatree/dist/jquery.dynatree.min.js') ?>"
        type="text/javascript" charset="utf-8"></script>
<link rel='stylesheet' type='text/css'
      href="<?= base_url('application/content/cms/javaScripts/dynatree/src/skin-vista/ui.dynatree.css') ?>">
<?= link_tag(base_url('application/content/cms/jquery-ui-1.8.24.custom.css')); ?>

<script>
    $(document).ready(function () {
        $('li.active').removeClass();
        $('#nav').addClass('active');

        $("#tree").dynatree({
            debugLevel: 0,
            onClick: function (node) {
                if (node.data.isFolder) {
                    $('#addMenu').attr('href', "#" + node.data.key);
                }
                $('#delete').attr('href', "#" + node.data.key);
                $('#edit').attr('href', "#" + node.data.key);
                if (node.data.isFolder) {
                    $('#addPage').attr('href', "#" + node.data.key);
                    $('#addGallery').attr('href', "#" + node.data.key);
                }
            },
            onPostInit: function () {
                $('#tree li:first').click();
            },
            onLazyRead: function (node) {
                node.appendAjax({
                    url: "<?= base_url('cms/menu/subElements')?>/" + node.data.key
                });
            },
            dnd: {
                preventVoidMoves: true,
                onDragStart: function () {
                    return true;
                },
                onDragEnter: function (node, sourceNode) {
                    if (node.data.isFolder) {
                        if (node.parent !== sourceNode.parent) {
                            return false;
                        }
                        return ["before"];
                    }
                },
                onDrop: function (node, sourceNode, hitMode) {
                    $.ajax({
                        url: "<?= base_url('cms/menu/changePriority')?>/" + node.getParent().data.key + '/' + node.data.key + '/' + sourceNode.data.key,
                        success: function (data) {
                            if (data == "_OK_") {
                                sourceNode.move(node, hitMode);
                            }
                            else {
                                alert(data);
                            }
                        }
                    });
                }
            },
            autoFocus: true,
            selectMode: 1,
            autoCollapse: false,
            keyboard: false,
            clickFolderMode: 1,
            children: <?= $tree ?>
        });

        $('#addMenu').click(function () {
            var node = $("#tree").dynatree("getActiveNode");
            if (node.data.isFolder) {
                $('#treeDialog').dialog('option', 'title', 'Добавление меню');
                $('#treeDialog').load("<?= base_url('cms/menu/addMenuView')?>/" + node.data.key, function () {
                    $("#treeDialog").dialog("open");
                });
                return false;
            }
            return false;
        });

        $('#edit').click(function () {
            var node = $("#tree").dynatree("getActiveNode");
            if (node.data.isFolder) {
                var menu = $(this).attr('href');
                menu = menu.substring(1, menu.length);
                $('#treeDialog').dialog('option', 'title', 'Редактирование меню');
                $('#treeDialog').load("<?= base_url('cms/menu/editMenuView')?>/" + menu, function () {
                    $("#treeDialog").dialog("open");
                });
            }
        });

        $('#delete').click(function () {
            if (confirm('Подтверждаете удаление?')) {
                var el = $(this).attr('href');
                el = el.substring(1, el.length);
                var node = $("#tree").dynatree("getActiveNode");
                var pNode = node.getParent();
                $.ajax({
                    url: (node.data.isFolder) ? "<?= base_url('cms/menu/deleteMenu')?>/" + el :
                        el[0] == 'p' ? "<?= base_url('cms/menu/deletePage')?>/" + pNode.data.key + '/' + el.substring(1, el.length) :
                        "<?= base_url('cms/menu/deleteGallery')?>/" + pNode.data.key + '/' + el.substring(1, el.length),
                    success: function (data) {
                        if (data == "_OK_") {
                            node.removeChildren();
                            node.remove();
                            $('#tree li:first').click();
                        }
                        else {
                            alert(data);
                        }
                    }
                });
            }
        });

        $('#addPage').click(function () {
            var node = $("#tree").dynatree("getActiveNode");
            if (node.data.isFolder) {
                $('#treeDialog').load("<?= base_url('cms/menu/addPageView')?>/" + node.data.key, function () {
                    $("#treeDialog").dialog("open");
                });
            }
        });

        $('#addGallery').click(function () {
            var node = $("#tree").dynatree("getActiveNode");
            if (node.data.isFolder) {
                $('#treeDialog').load("<?= base_url('cms/menu/addGalleryView')?>/" + node.data.key, function () {
                    $("#treeDialog").dialog("open");
                });
            }
        });

        $("#treeDialog").dialog({
            autoOpen: false,
            modal: true,
            resizable: false,
            height: 490,
            width: 540,
            position: ["center", "center"],
            buttons: {
                "Применить": function () {
                    $('#form').submit();
                },
                "Закрыть": function () {
                    $(this).dialog("close");
                }
            }
        });
    });
</script>

<div class="container buttons"/>
<div class="btn-toolbar">
    <div class="btn-group">
        <a href="#" id="addMenu" class="btn btn-small btn-menu" style="margin-left:10px"><i class="icon-folder-close-alt"></i> Добавить пункт меню</a>
        <a href="#" id="edit" class="btn btn-small btn-menu"><i class="icon-edit"></i> Изменить пункт меню</a>
        <a href="#" id="addPage" class="btn btn-small btn-menu"><i class="icon-file"></i> Добавить страницу</a>
        <a href="#" id="addGallery" class="btn btn-small btn-menu"><i class="icon-picture"></i> Добавить галлерею</a>
        <a href="#" id="delete" class=" btn btn-small btn-menu"><i class="icon-remove-sign"></i> Удалить элемент</a>
    </div>
</div>
<div id="tree" style="margin:10px;"></div>
<div id="treeDialog" title=""></div>
<div class="container">

</div>
</body>
