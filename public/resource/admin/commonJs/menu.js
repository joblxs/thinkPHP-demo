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
        elem: '#munu-table',
        url: '/admin/system.menu/apiIndex',
        page: false,
        cols: [[
            {type: 'checkbox'},
            {field: 'title', minWidth: 200, title: '权限名称'},
            {field: 'href', title: '菜单url'},
            // {field: 'orderNumber', width: 80, align: 'center', title: '排序号'},
            {
                field: 'isMenu', width: 80, align: 'center', templet: function (d) {
                    if (d.pid == 1) {
                        return '<span class="layui-badge layui-bg-gray">按钮</span>';
                    }
                    if (d.pid == 0) {
                        return '<span class="layui-badge layui-bg-blue">目录</span>';
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
        treetable.expandAll('#munu-table');
    });

    $('#btn-fold').click(function () {
        treetable.foldAll('#munu-table');
    });

    //监听工具条
    table.on('tool(munu-table)', function (obj) {
        var data = obj.data;
        var layEvent = obj.event;

        if (layEvent === 'del') {
            layer.msg('删除' + data.id);
        } else if (layEvent === 'edit') {
            layer.msg('修改' + data.id);
        }
    });
});