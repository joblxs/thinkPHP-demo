layui.use(['jquery', 'layer', 'miniAdmin'], function () {
    var $ = layui.jquery,
        layer = layui.layer,
        miniAdmin = layui.miniAdmin,
        element = layui.element;

    var options = {
        iniUrl: "/nav/index/api",    // 初始化接口
        urlHashLocation: true,      // 是否打开hash定位
        bgColorDefault: false,      // 主题默认配置
        multiModule: false,          // 是否开启多模块
        menuChildOpen: false,       // 是否默认展开菜单
        loadingTime: 0,             // 初始化加载时间
        pageAnim: true,             // iframe窗口动画
        maxTabNum: 10,              // 最大的tab打开数量
    };
    miniAdmin.render(options);

    // 给带有tips类的链接绑定鼠标悬浮提示事件
    $('.tips').on('mouseenter', function(){
        var tipsText = $(this).attr('lay-tips');
        layer.tips(tipsText, this, {
            tips: [3, '#16b777']
        });
    }).on('mouseleave', function(){
        layer.closeAll('tips');
    });

    // 添加点击事件
    $('.layui-card.tips').on('click', function(){
        var id = $(this).data('id');
        $.ajax({
            type: 'GET', // 提交方式
            url: '/nav/index/clickLink?id='+id, // 你的后端接口地址
            success: function (res) {
                // 请求成功，根据后端返回的数据进行处理
                if (res.code == 200) {
                    console.log(res.data);
                    // 使用 window.open 根据 data-target 的值来决定是新窗口打开还是当前窗口跳转
                    if(res.data.target === '_blank') {
                        window.open(res.data.link, '_blank');
                    } else {
                        window.location.href = res.data.link;
                    }
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
    })
});