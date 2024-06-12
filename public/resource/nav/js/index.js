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
});