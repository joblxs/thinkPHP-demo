layui.use(['table', 'treetable'], function () {
    var $ = layui.jquery;
    var table = layui.table;
    var treetable = layui.treetable;

    // 渲染表格
    layer.load(2);
    treetable.render({
        treeColIndex: 1,
        treeSpid: 0,
        treeIdName: 'id',
        treePidName: 'pid',
        elem: '#currentTable',
        url: '/admin/system.menu/apiIndex',
        page: false,
        cols: [[
            {type: 'checkbox'},
            {field: 'title', minWidth: 200, title: '菜单名称'},
            // {field: 'icon', width: 80, title: '图标', templet: ea.table.icon},
            {field: 'href', title: '菜单链接'},
            // {field: 'orderNumber', width: 80, align: 'center', title: '排序号'},
            {
                field: 'isMenu', width: 80, align: 'center', templet: function (d) {
                    if (d.type == 2) {
                        return '<span class="layui-badge layui-bg-gray">按钮</span>';
                    } else if (d.type == 0) {
                        return '<span class="layui-badge layui-bg-blue">模块</span>';
                    } else {
                        return '<span class="layui-badge-rim">菜单</span>';
                    }
                }, title: '类型'
            },
            {templet: '#auth-state', width: 120, align: 'center', title: '操作'}
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
            content: '/admin/system.menu/add',
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
        } else if (layEvent === 'edit') {
            layer.msg('修改' + data.id);
        }
    });
});