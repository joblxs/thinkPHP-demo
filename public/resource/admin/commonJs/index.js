layui.use(['jquery', 'layer', 'miniAdmin','miniTongji'], function () {
    var $ = layui.jquery,
        layer = layui.layer,
        miniAdmin = layui.miniAdmin

    var options = {
        iniUrl: "/admin/index/menu",    // 初始化接口
        clearUrl: "api/clear.json", // 缓存清理接口
        urlHashLocation: true,      // 是否打开hash定位
        bgColorDefault: false,      // 主题默认配置
        multiModule: true,          // 是否开启多模块
        menuChildOpen: false,       // 是否默认展开菜单
        loadingTime: 0,             // 初始化加载时间
        pageAnim: true,             // iframe窗口动画
        maxTabNum: 20,              // 最大的tab打开数量
    };
    miniAdmin.render(options);

    $('.login-out').on("click", function () {
        $.ajax({
            type: 'POST',
            url: '/admin/auth/logOut',
            contentType: 'application/json',
            success: function (res) {
                // 请求成功，根据后端返回的数据进行处理
                if (res.code == 200) {
                    layer.msg('退出登录成功', function () {
                        window.location = '/admin/auth/login';
                    });
                } else {
                    layer.msg(res.msg);
                }
            },
            error: function (xhr, status, error) {
                // 请求失败，根据错误类型进行处理
                layer.msg('提交失败：' + error);
            }
        });
    });
});