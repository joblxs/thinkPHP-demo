layui.use(['form', 'table'], function () {
    var $ = layui.jquery,
        form = layui.form,
        table = layui.table;

    table.render({
        elem: '#currentTableId',
        url: '/admin/nav.link/apiIndex',
        toolbar: '#toolbarDemo',
        defaultToolbar: ['filter', 'exports', 'print', {
            title: '提示',
            layEvent: 'LAYTABLE_TIPS',
            icon: 'layui-icon-tips'
        }],
        cols: [[
            {type: "checkbox", width: 50},
            {field: 'id', width: 80, title: 'ID', sort: true},
            {
                title: '所属分类', width: 180, templet: function (d) {
                    return '<i class="'+d.icon+'"></i> ' + d.title;
                }
            },
            {
                title: '链接名称', width: 200, templet: function (d) {
                    return '<a href="'+d.link+'" title="'+d.link_desc+'" target="_blank">'+d.link_name+'</a>';
                }
            },
            {
                title: '图标', width: 100, templet: function (d) {
                    if (d.link_img) {
                        return '<img src="'+d.link_img+'" alt="'+d.link_img+'" height="30">';
                    } else {
                        return ''
                    }
                }
            },
            {title: '状态', width:100, templet: function (d) {
                // 根据 status 的值来设置 checked 属性
                var checked = d.status == 0 ? 'checked' : '';
                return '<input type="checkbox" name="status" id='+d.id+' lay-skin="switch" lay-filter="templet-status" ' + checked + '>';
            }},
            {title: '密码访问', width:100, templet: function (d) {
                // 根据 status 的值来设置 checked 属性
                var checked = d.is_pass == 1 ? 'checked' : '';
                return '<input type="checkbox" name="status" id='+d.id+' lay-skin="switch" lay-filter="templet-pass" ' + checked + '>';
            }},
            {title: '操作', minWidth: 150, toolbar: '#currentTableBar', align: "center"}
        ]],
        limits: [10, 15, 20, 25, 50, 100],
        limit: 15,
        page: true,
        skin: 'line'
    });

    // 状态 - 开关操作
    form.on('switch(templet-status)', function(obj){
        var id = this.id;
        var status = obj.elem.checked ? 0 : 1;
        $.ajax({
            type: 'POST', // 提交方式
            url: '/admin/nav.link/status', // 你的后端接口地址
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
    form.on('switch(templet-pass)', function(obj){
        var id = this.id;
        var is_pass = obj.elem.checked ? 1 : 0;
        $.ajax({
            type: 'POST', // 提交方式
            url: '/admin/nav.link/password', // 你的后端接口地址
            contentType: 'application/json', // 发送信息至服务器时内容编码类型
            data: JSON.stringify({id: id,is_pass: is_pass}), // 表单数据
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

    // 监听搜索操作
    form.on('submit(data-search-btn)', function (data) {
        var result = JSON.stringify(data.field);
        layer.alert(result, {
            title: '最终的搜索信息'
        });

        //执行搜索重载
        table.reload('currentTableId', {
            page: {
                curr: 1
            }
            , where: {
                searchParams: result
            }
        }, 'data');

        return false;
    });

    /**
     * toolbar监听事件
     */
    table.on('toolbar(currentTableFilter)', function (obj) {
        if (obj.event === 'add') {  // 监听添加操作
            var index = layer.open({
                title: '添加',
                type: 2,
                shade: 0.2,
                maxmin:true,
                shadeClose: true,
                area: ['100%', '100%'],
                content: '/admin/nav.link/add?cid=1',
            });
            $(window).on("resize", function () {
                layer.full(index);
            });
        } else if (obj.event === 'delete') {  // 监听删除操作
            var checkStatus = table.checkStatus('currentTableId')
                , data = checkStatus.data;
            layer.alert(JSON.stringify(data));
        }
    });

    //监听表格复选框选择
    table.on('checkbox(currentTableFilter)', function (obj) {
        console.log(obj)
    });

    table.on('tool(currentTableFilter)', function (obj) {
        var data = obj.data;
        if (obj.event === 'edit') {

            var index = layer.open({
                title: '修改',
                type: 2,
                shade: 0.2,
                maxmin:true,
                shadeClose: true,
                area: ['100%', '100%'],
                content: '/admin/nav.link/edit?id=' + data.id,
            });
            $(window).on("resize", function () {
                layer.full(index);
            });
            return false;
        } else if (obj.event === 'delete') {
            layer.confirm('真的删除行么', function (index) {
                obj.del();
                layer.close(index);
            });
        }
    });

});