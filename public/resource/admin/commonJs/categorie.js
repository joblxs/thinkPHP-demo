layui.use(['table', 'treetable', 'form'], function () {
    var $ = layui.jquery;
    var table = layui.table;
    var form = layui.form;
    var treetable = layui.treetable;

    // 渲染表格
    layer.load(2);
    treetable.render({
        treeColIndex: 1,
        treeSpid: 0,
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
                title: '类型', width: 100, align: 'center', templet: function (d) {
                    if (d.pid == 0) {
                        return '<span class="layui-badge layui-bg-blue">一级分类</span>';
                    } else {
                        return '<span class="layui-badge-rim layui-bg-red">二级分类</span>';
                    }
                }
            },
            {title: '状态', width:100, templet: function (d) {
                // 根据 status 的值来设置 checked 属性
                var checked = d.status == 0 ? 'checked' : '';
                return '<input type="checkbox" name="status" id='+d.id+' lay-skin="switch" lay-filter="templet-status" ' + checked + '>';
            }},
            {templet: '#auth-state', width: 220, align: 'center', title: '操作'}
        ]],
        done: function () {
            layer.closeAll('loading');
        }
    });

    // 状态 - 开关操作
    form.on('switch(templet-status)', function(obj){
        var id = this.id;
        var status = obj.elem.checked ? 0 : 1;
        $.ajax({
            type: 'POST', // 提交方式
            url: '/admin/nav.categorie/status', // 你的后端接口地址
            contentType: 'application/json', // 发送信息至服务器时内容编码类型
            data: JSON.stringify({id: id,status: status}), // 表单数据
            success: function (res) {
                // 请求成功，根据后端返回的数据进行处理
                if (res.code == 200) {
                    layer.msg(res.msg);
                } else {
                    layer.msg(res.msg);
                }
            },
            error: function (xhr, status, error) {
                // 请求失败，根据错误类型进行处理
                layer.msg('提交失败：' + error);
            }
        });
        return false;
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
                content: '/admin/nav.link/add?cid=' + data.id,
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