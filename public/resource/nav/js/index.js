layui.use(['jquery', 'layer', 'miniAdmin', 'flow', 'typeahead', 'form'], function () {
    var $ = layui.jquery,
        layer = layui.layer,
        flow = layui.flow,
        form = layui.form,
        typeahead = layui.typeahead,
        util = layui.util,
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

    // 切换搜索引擎
    $('#filter-search li').on('click', function() {
        var tipsHref = $(this).attr('lay-href');
        $('#supplier').attr('data-href', tipsHref)
    })
    // 百度联想词
    typeahead.render({
        elem: '#supplier',
        source: function(query, process) {

            var script = document.createElement('script');
            var callbackName = 'suggestionCallback'; // 回调函数名称
            script.src = 'https://sp0.baidu.com/5a1Fazu8AA54nxGko9WTAnF6hhy/su?wd=' + encodeURIComponent(query) + '&cb=' + callbackName;
            document.head.appendChild(script);
            // 定义回调函数来处理返回的数据
            window[callbackName] = function(data) {
                var result = [];
                if (data.s && data.s.length > 0) {
                    data.s.forEach(function(suggestion) {
                        result.push({
                            text: suggestion, // 显示的文本
                            value: suggestion // 值，可以根据需要进行修改
                        });
                    });
                }
                process(result); // 将结果传递给typeahead
            };
        },
        // 显示的文本
        displayText: function(item) {
            var tabItems = document.querySelectorAll('.layui-tab-item.layui-show');
            if (tabItems) {
                // 如果存在，去除动画
                var style = document.createElement('style');
                style.type = 'text/css';
                style.innerHTML = `
                    .layui-tab-item.layui-show {
                        animation: none !important;
                        -webkit-animation: none !important;
                    }
                `;
                document.head.appendChild(style);
            }
            return item.text + '(' + item.value + ')';
        },
        // 选择某一项后的回调
        afterSelect: function(item){
            var keyword = $('#supplier').val(); // 获取输入框的值
            var href = $('#supplier').attr('data-href'); // 获取输入框的值
            if(!keyword){
                return $('#supplier').focus()
            };
            var url = href + encodeURIComponent(keyword); // 构建搜索 URL
            window.open(url, '_blank'); // 在新标签页打开搜索结果
        }
    });
    // 监听输入框的 keyup 事件，当按下回车键时触发搜索
    $('#supplier').keyup(function(event) {
        if (event.keyCode === 13) {
            searchFunction();
        }
    });

    // 监听搜索图标的点击事件
    $('#search-icon').click(function() {
        searchFunction();
    });
    // 定义搜索函数
    function searchFunction() {
        var keyword = $('#supplier').val(); // 获取输入框的值
        var href = $('#supplier').attr('data-href'); // 获取输入框的值
        if(!keyword){
            return $('#supplier').focus()
        };
        var url = href + encodeURIComponent(keyword); // 构建搜索 URL
        window.open(url, '_blank'); // 在新标签页打开搜索结果
    }

    // 图片懒加载
    flow.lazyimg({
        elem: '#flow-lazyimg img',
        scrollElem: '#flow-lazyimg' // 触发事件
    });

    // 监听 Tab 切换事件
    $('.layui-tab.layui-tab-brief').on('click', function() {
        var layFilter = this.getAttribute('lay-filter')
        element.on('tab('+layFilter+')', function(data){
            flow.lazyimg({
                elem: '#flow-lazyimg img',
                scrollElem: '#flow-lazyimg' // 触发事件
            });
        });
    })

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
    $('.layui-card.tips').on('click', function() {
        var id = $(this).data('id');
        var is_pass = $(this).data('is_pass');
        var password = ''
        if (is_pass == 1) {
            layer.prompt({
                title: ['请输入密令', 'color: #fff'], anim: 1, formType: 1, shade: [0.3, '#FFF']
            }, function(value, index, elem){
                if(value === '') return elem.focus();
                password = util.escape(value)
                // 关闭 prompt
                // layer.close(index);
                // 继续执行 AJAX 请求
                performAjax(id, password, index);
            });
        } else {
            performAjax(id, password);
        }
        return false;
    })

    function performAjax(id, password, index) {
        $.ajax({
            type: 'GET',
            url: '/nav/index/clickLink?id=' + id + '&password=' + (password || ''),
            success: function (res) {
                // 请求成功，根据后端返回的数据进行处理
                if (res.code == 200) {
                    layer.close(index);
                    // 使用 window.open 根据 data-target 的值来决定是新窗口打开还是当前窗口跳转
                    if(res.data.target === '_blank') {
                        window.open(res.data.link, '_blank');
                    } else {
                        window.location.href = res.data.link;
                    }
                } else {
                    layer.msg(res.msg, {icon: 4, shade: [0.5, '#FFF']});
                }
            },
            error: function (xhr, status, error) {
                // 请求失败，根据错误类型进行处理
                layer.msg('提交失败：' + error);
            }
        });
    }
});