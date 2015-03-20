<body>
<?php include_once 'application/views/cms/menu.php'; ?>
<script src="<?= base_url('application/content/cms/javaScripts/dynatree/jq.context/jquery.contextMenu.js') ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?= base_url('application/content/cms/javaScripts/dynatree/jquery/jquery.cookie.js') ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?= base_url('application/content/cms/javaScripts/dynatree/dist/jquery.dynatree.js') ?>" type="text/javascript" charset="utf-8"></script>
<link rel='stylesheet' type='text/css' href="<?= base_url('application/content/cms/javaScripts/dynatree/src/skin-vista/ui.dynatree.css') ?>">
<?= link_tag(base_url('application/content/cms/jquery-ui.css')); ?>
<?= link_tag(base_url('application/content/cms/javaScripts/dynatree/jq.context/jquery.contextMenu.css')); ?>
<script>

    function bindContextMenu(span) {
        $(span).contextMenu({menu: "contextMenu"}, function (action, el, pos) {

            switch (action) {
                case "create":
                    $('#addMenu').click();
                    break;
                case "edit":
                    $('#edit').click();
                    break;
                case "add_obj":
                    $('#addObject').click();
                    break;
                case "add_page":
                    $('#addPage').click();
                    break;
                case "add_gallery":
                    $('#addGallery').click();
                    break;
                case "delete":
                    $('#delete').click();
                    break;
                default:
                    break;
            }
        });
    }
    ;

    function bindContextMenuPage(span) {
        $(span).contextMenu({menu: "contextMenuPage"}, function (action, el, pos) {
            switch (action) {
                case "goto":
                    var $link = $('<a href="<?= base_url('cms/page/editView')?>/' + $('#goto').data('id') + '" target="_blank"></a>').appendTo($('body'));

                    if (document.createEvent) {
                        var theEvent = document.createEvent("MouseEvent");
                        theEvent.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
                        $link.get(0).dispatchEvent(theEvent);
                    }
                    else if ($link.get(0).click) $link.get(0).click();
                    $link.remove();
                    break;
                case "delete":
                    $('#delete').click();
                    break;
                default:
                    break;
            }
        });
    }
    ;

    $(document).ready(function () {
        $('li.active').removeClass();
        $('#nav').addClass('active');

        $("#tree").dynatree({
            debugLevel: 0,
            onCreate: function (node, span) {
                if (node.data.isFolder)
                    bindContextMenu(span);
                if (!node.data.isFolder && node.data.key[0]=='p')
                    bindContextMenuPage(span);
            },
            onClick: function (node) {
                if (node.data.isFolder) {
                    $('#addMenu').attr('href', "#" + node.data.key);
                }
                $('#delete').attr('href', "#" + node.data.key);
                $('#edit').attr('href', "#" + node.data.key);
                if (node.data.isFolder) {
                    $('#addPage').attr('href', "#" + node.data.key);
                    $('#addGallery').attr('href', "#" + node.data.key);
                } else {
                    id = node.data.key.substring(1, node.data.key.length);
                    $('#goto').data('id', id);
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

                    $("#progress_dialog").dialog('open');
                    $.ajax({
                        url: "<?= base_url('cms/menu/changePriority')?>/" + node.getParent().data.key + '/' + node.data.key + '/' + sourceNode.data.key,
                        success: function (data) {
                            if (data == "_OK_") {
                                sourceNode.move(node, hitMode);
                            }
                            else {
                                alert(data);
                            }
                            $("#progress_dialog").dialog('close');
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


        $('#tree').on('mousedown', '.dynatree-node', function (e) {
            if (e.button == 2){
                $(this).trigger('click');
            return false;}
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
            var node = $("#tree").dynatree("getActiveNode");
            if (confirm('Подтверждаете удаление: "' + node.data.title + '" ?')) {
                var el = $(this).attr('href');
                el = el.substring(1, el.length);
                var pNode = node.getParent();
                var url = '';
                if (node.data.isFolder) {
                    url = "<?= base_url('cms/menu/deleteMenu')?>/" + el;
                }
                else {
                    if (el[0] == 'p') {
                        url = "<?= base_url('cms/menu/deletePage')?>/" + pNode.data.key + '/' + el.substring(1, el.length);
                    }
                    if (el[0] == 'o') {
                        url = "<?= base_url('cms/menu/deleteObject')?>/" + pNode.data.key + '/' + el.substring(1, el.length);
                    }
                    else {
                        url = "<?= base_url('cms/menu/deleteGallery')?>/" + pNode.data.key + '/' + el.substring(1, el.length)
                    }
                }

                $.ajax({
                    url: url,
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
                $('#treeDialog').dialog('option', 'title', 'Добавление страницы');
                $('#treeDialog').load("<?= base_url('cms/menu/addPageView')?>/" + node.data.key, function () {
                    $("#treeDialog").dialog("open");
                });
            }
        });

        $('#addObject').click(function () {
            var node = $("#tree").dynatree("getActiveNode");
            if (node.data.isFolder) {
                $('#treeDialog').dialog('option', 'title', 'Добавление объекта');
                $('#treeDialog').load("<?= base_url('cms/menu/addObjectView')?>/" + node.data.key, function () {
                    $("#treeDialog").dialog("open");
                });
            }
        });

        $('#addGallery').click(function () {
            var node = $("#tree").dynatree("getActiveNode");
            if (node.data.isFolder) {
                $('#treeDialog').dialog('option', 'title', 'Добавление галереи');
                $('#treeDialog').load("<?= base_url('cms/menu/addGalleryView')?>/" + node.data.key, function () {
                    $("#treeDialog").dialog("open");
                });
            }
        });

        $("#treeDialog").dialog({
            autoOpen: false,
            modal: true,
            resizable: false,
            height: 500,
            width: 600,
            position: {my: "center center", at: "center center"},
            buttons: {
                "Применить": function () {
                    $('#form').submit();
                },
                "Закрыть": function () {
                    $(this).dialog("close");
                }
            }
        });

        $("#progress_dialog").dialog({
            autoOpen: false,
            closeOnEscape: false,
            resizable: false,
            open: function () {
                $("#progressbar").progressbar({
                    value: false
                });
            }
        });

    });
</script>

<div class="container buttons"></div>
<div class="btn-toolbar">
    <div class="btn-group">
        <a href="#" id="addMenu" class="btn btn-small btn-menu" style="margin-left:10px"><i class="icon-folder-close-alt"></i> Добавить пункт меню</a>
        <a href="#" id="edit" class="btn btn-small btn-menu"><i class="icon-edit"></i> Изменить пункт меню</a>
        <a href="#" id="addPage" class="btn btn-small btn-menu"><i class="icon-file"></i> Добавить страницу</a>
        <a href="#" id="addObject" class="btn btn-small btn-menu"><i class="icon-briefcase"></i> Добавить объект</a>
        <a href="#" id="addGallery" class="btn btn-small btn-menu"><i class="icon-picture"></i> Добавить галерею</a>
        <a href="#" id="delete" class=" btn btn-small btn-menu"><i class="icon-remove-sign"></i> Удалить элемент</a>
    </div>
</div>
<div id="tree" style="margin:10px;"></div>
<div id="treeDialog" title=""></div>
<div class="container"></div>
<ul id="contextMenu" class="contextMenu">
    <li class=""><a href="#create"><i class="icon-folder-close-alt"></i> Добавить пункт меню</a></li>
    <li class=""><a href="#edit"><i class="icon-edit"></i> Изменить пункт меню</a></li>
    <li class=""><a href="#add_page"><i class="icon-file"></i> Добавить страницу</a></li>
    <li class=""><a href="#add_gallery"><i class="icon-picture"></i> Добавить галерею</a></li>
    <li class=""><a href="#add_obj"><i class="icon-briefcase"></i> Добавить объект</a></li>
    <li class=""><a href="#delete"><i class="icon-remove-sign"></i> Удалить</a></li>
</ul>

<ul id="contextMenuPage" class="contextMenu">
    <li class=""><a id="goto" href="#goto"><i class="icon-file-alt"></i>Перейти >>></a></li>
    <li class=""><a href="#delete"><i class="icon-remove-sign"></i> Удалить</a></li>
</ul>

<div id="progress_dialog" title="Выполнение операции...">
    <div id="progressbar"></div>
</div>

</body>
