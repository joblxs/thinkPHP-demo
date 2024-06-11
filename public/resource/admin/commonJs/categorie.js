layui.use(['table', 'treetable'], function () {
    var $ = layui.jquery;
    var table = layui.table;
    var treetable = layui.treetable;

    // 渲染表格
    layer.load(2);
    treetable.render({
        treeColIndex: 1,
        treeSpid: 1,
        treeIdName: 'id',
        treePidName: 'pid',
        elem: '#currentTable',
        url: '/admin/nav.categorie/apiIndex',
        page: false,
        cols: [[
            {type: 'checkbox'},
            {field: 'title', minWidth: 200, title: '分类名称'},
            {field: 'id', minWidth: 20, title: 'ID'},
            {
                field: 'isMenu', width: 100, align: 'center', templet: function (d) {
                    if (d.pid == 0) {
                        return '<span class="layui-badge layui-bg-blue">顶级分类</span>';
                    } else if (d.pid == 1) {
                        return '<span class="layui-badge-rim layui-bg-red">一级分类</span>';
                    } else {
                        return '<span class="layui-badge-rim">二级分类</span>';
                    }
                }, title: '类型'
            },
            {templet: '#auth-state', width: 220, align: 'center', title: '操作'}
        ]],
        done: function () {
            layer.closeAll('loading');
        }
    });

    $('#btn-expand').click(function () {
        treetable.expandAll('#currentTable');
    });

    $('#btn-fold').click(function () {
        treetable.foldAll('#currentTable');
    });

    $('#btn-add').click(function () {
        var index = layer.open({
            title: '添加',
            type: 2,
            shade: 0.2,
            maxmin:true,
            shadeClose: true,
            area: ['100%', '100%'],
            content: '/admin/nav.categorie/add?pid=0',
        });
        $(window).on("resize", function () {
            layer.full(index);
        });
    });

    //监听工具条
    table.on('tool(currentTable)', function (obj) {
        var data = obj.data;
        var layEvent = obj.event;

        if (layEvent === 'del') {
            layer.msg('删除' + data.id);
        } else if (layEvent === 'addChild') {
            var index = layer.open({
                title: '添加',
                type: 2,
                shade: 0.2,
                maxmin:true,
                shadeClose: true,
                area: ['100%', '100%'],
                content: '/admin/nav.categorie/add?pid=' + data.id,
            });
            $(window).on("resize", function () {
                layer.full(index);
            });
        } else if (layEvent === 'edit') {
            var index = layer.open({
                title: '修改',
                type: 2,
                shade: 0.2,
                maxmin:true,
                shadeClose: true,
                area: ['100%', '100%'],
                content: '/admin/nav.categorie/edit?id=' + data.id,
            });
            $(window).on("resize", function () {
                layer.full(index);
            });
        }
    });
});