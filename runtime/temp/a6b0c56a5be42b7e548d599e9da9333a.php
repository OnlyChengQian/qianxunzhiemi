<?php /*a:3:{s:72:"F:\aProjectDevelopment\qianxun\vendor\weiwei\api-doc\src\view\index.html";i:1550655681;s:71:"F:\aProjectDevelopment\qianxun\vendor\weiwei\api-doc\src\view\base.html";i:1550655681;s:71:"F:\aProjectDevelopment\qianxun\vendor\weiwei\api-doc\src\view\head.html";i:1550655681;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <link href='<?php echo $static; ?>/css/bootstrap.min.css' rel='stylesheet' type='text/css'>
    <link href='<?php echo $static; ?>/css/style.css' rel='stylesheet' type='text/css'>
    <script src="<?php echo $static; ?>/js/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script src="<?php echo $static; ?>/js/bootstrap.min.js" type="text/javascript"></script>
</head>

<script src="<?php echo $static; ?>/js/zTree_v3/js/jquery.ztree.core-3.5.min.js" type="text/javascript"></script>
<link href="<?php echo $static; ?>/js//zTree_v3/css/zTreeStyle/zTreeStyle.css" rel="stylesheet" />
<style type="text/css">
    .tab-content{width: 100%;height: 100%; overflow: hidden;}
    .tab-content .tab-pane{width: 100%;height: 100%;  overflow: hidden;}
</style>

<body>

<div class="zt_header">
    <?php echo $title; ?>
    <span><?php echo $version; ?></span>
    <span class="zt_download"><?php echo $copyright; ?></span>
</div>
<div class="zt_main">
    <div class="zt_navi">
        <div class="zt_navi_inner">
            <div class="ztree" id="zt_navi_tree"></div>
        </div>
    </div>
    <div class="zt_content" style="overflow: hidden;">
        <ul id="apiTab" class="nav nav-tabs">
            <li id="index_tab" class="active"><a href="#index_tab_content" data-toggle="tab">搜索</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="index_tab_content"><iframe id="zt_text" src="<?php echo $root; ?>/doc/search" width="100%" height="100%" frameborder="0"></iframe></div>
        </div>
    </div>
</div>


<script type="text/javascript">
    var setting = {
        view: {
            selectedMulti: false
        },
        callback: {
            beforeClick: beforeClick,
            onClick: onClick
        },
        data:{
            key:{
                children: "actions",
                name: "title",
                url:"",
            }
        }
    };
    function beforeClick(treeId, treeNode, clickFlag) {
        if (treeNode.isParent) {
            zTree.expandNode(treeNode);
            return false;
        }else if(!treeNode.isText){
            return false;
        }
        return true;
    }

    function onClick(event, treeId, treeNode, clickFlag) {
        if (!treeNode.isParent) {
            if(treeNode.isText){
                var tab_id = treeNode.tId;
                var name = treeNode.title;
                loadText(tab_id, name, treeNode.name);
            }
        }
    }

    function closeTab(tab) {
        var id = $(tab).parent().parent().attr('id');
        $("#" + id).remove();
        $("#" + id + "_content").remove();
        //显示最后一个tab
        id = $("#apiTab").find('>li:last').attr('id');
        $("#"+id+" > a").click();
    }

    function loadText(tab_id, title, name){
        //创建tab
        var tab_id = tab_id + "_tab";
        if($("#"+tab_id).length  == 0)
        {
            // class="active"
            $("#apiTab").append('<li id="'+ tab_id +'"><a href="#' + tab_id + '_content" data-toggle="tab">'+ title +' <span class="glyphicon glyphicon-remove" onclick="closeTab(this)" style="cursor:pointer"></span></a></li>');
        }else{
            $("#"+tab_id+" > a").click();
        }
        var tab_content = tab_id + "_content";
        if($("#"+tab_content).length == 0)
        {
            // active
            $(".zt_content > .tab-content").append('<div class="tab-pane fade in" id="'+ tab_content +'"><iframe id="zt_text" src="" width="100%" height="100%" frameborder="0"></iframe></div>');
            $("#"+tab_id+" > a").click();
        }
        var content_text = $("#" + tab_content).find("#zt_text");
        $(content_text).html("<p>加载中……</p>");
        $(content_text).attr("src","<?php echo $root; ?>/doc/info?name="+name);
        initHeight();
    }
    var zTree;
    $(document).ready(function(){
        $.ajax({
            url: "<?php echo $root; ?>/doc/list",
            dataType: "json",
            success: function(data){
                var zNodes = data.list;
                zTree = $.fn.zTree.init($("#zt_navi_tree"), setting, zNodes);
                var currentId = data.firstId || '<?php str_replace('\\', '\\\\', $doc) ?>';
                if(currentId) {
                    var node = zTree.getNodeByParam("name", currentId);
                    loadText(node.tId, node.title, node.name);
                    zTree.selectNode(node);
                }
            }
        });
        initHeight();
    });

    function initHeight()
    {
        $(".tab-content").height($(".zt_content").height() - $("#apiTab").height());
    }
</script>

</body>
</html>