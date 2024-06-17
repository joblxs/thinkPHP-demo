/**
 * 带有输入提示功能的输入框.
 */
layui.define(['laytpl', 'jquery'], function (exports) {
    var $ = layui.jquery;
    var laytpl = layui.laytpl;
    var hint = layui.hint();
    // 控件名称
    var MOD_NAME = 'typeahead';

    var THIS = 'layui-this';
    var DISABLED = 'layui-disabled';

    var CLASS = 'layui-typeahead';
    var CLASS_INPUT = CLASS + '-input';
    var CLASS_SELECTED = CLASS + '-selected';

    // 控件主体模板
    var TPL_MAIN = [
        '<dl class="layui-anim layui-anim-upbit ', CLASS , '" style="max-height: {{= d.data.maxHeight }};">',
        '</dl>',
    ].join('');

    // 操作当前实例
    var thisModule = function () {
        var that = this
            ,options = that.config
            ,id = options.id || that.index;

        thisModule.that[id] = that; //记录当前实例对象

        return {
            config: options
            //重置实例
            , reload: function (options) {
                that.reload.call(that, options);
            }
        }
    };
    //记录所有实例
    thisModule.that = {};
    //获取当前实例对象
    thisModule.getThis = function (id) {
        var that = thisModule.that[id];
        if (!that) hint.error(id ? (MOD_NAME + ' instance with ID \'' + id + '\' not found') : 'ID argument required');
        return that
    };
    //获取当前实例对象的options
    thisModule.getThisOptions = function (id) {
        var that = thisModule.getThis(id);
        if (!that) return;
        return that.config;
    };

    // 构造器
    var Class = function (options) {
        var that = this;
        that.index = ++typeahead.index;
        that.config = $.extend({}, that.config, typeahead.config, options);
        that.render();
    };

    // 默认配置
    Class.prototype.config = {
        maxHeight: "300px",
    };

    // 渲染控件
    Class.prototype.render = function () {
        var that = this
            ,options = that.config;

        var elem = $(options.elem);
        if (!elem[0]) return;
        options.elem = elem;

        // 保存当前实例的ID
        options.id = options.id || elem.attr('id') || that.index;

        // 渲染Dom
        renderDom(options);
        //事件
        that.events();
    }

    //重载实例
    Class.prototype.reload = function (options) {
        var that = this;

        //防止数组深度合并
        layui.each(options, function (key, item) {
            if (layui.type(item) === 'array') delete that.config[key];
        });

        that.config = $.extend(true, {}, that.config, options);
        that.render();
    };

    // 事件
    Class.prototype.events = function () {
        var that = this
            ,options = that.config
            ,elem = $(options.elem)
            ,dl = elem.next()
            ,input = elem;

        // 注册事件
        // 用户输入时事件
        // 输入内容变化时
        input.on('input propertychange', function (e) {
            if (e.target.composing) return false;
            var othis = $(this);
            if (othis.is('[disabled]') || othis.is('[readonly]')) return false;
            return search(e, options);
        });
        // 汉字输入监听
        input.on('compositionstart', function (e) {
            e.target.composing = true;
        });
        input.on('compositionend', function (e) {
            e.target.composing = false;
            return search(e, options);
        });
        // input 键盘事件
        input.on('keydown', function (e) { // 键盘按下
            var othis = $(this);
            if (othis.is('[disabled]') || othis.is('[readonly]')) return;
            var keyCode = e.keyCode;
            // Tab键隐藏
            if (keyCode === 9) {
                hideDown(elem);
                return;
            }
            // Enter 键
            if (keyCode === 13) {
                e.preventDefault();
                dl.children('dd.' + THIS).trigger('click');
                return;
            }
            // 后面只处理Up、Down键
            if (keyCode !== 38 && keyCode !== 40) {
                return;
            }
            // 当下拉框没有显示时
            if (!dl.hasClass(CLASS_SELECTED)) {
                // 不处理Up键
                e.preventDefault();
                // Down键按下时，展开下拉框
                if (keyCode === 40) {
                    search(e, options);
                }
                return;
            }
            if (keyCode === 38) setThisDd('prev'); // Up 键
            if (keyCode === 40) setThisDd('next'); // Down 键
            // 标注 dd 的选中状态
            function setThisDd(prevNext) {
                e.preventDefault();
                var thisDd = dl.children('dd.' + THIS);
                var nearDd = thisDd[prevNext]();
                // 没有上一个或下一个科目时
                if (nearDd.index() < 0) {
                    // 本来就一个都没选中时
                    // 已处于最后一行，next
                    // 已处于第一行，prev
                    nearDd = prevNext === 'next' ? dl.children().eq(0) : dl.children(':last');
                }
                nearDd.addClass(THIS).siblings().removeClass(THIS); // 标注样式
                followScroll(elem); // 定位滚动条
            }
        });
        // 点击下拉框中的项目，选中该项目并把text设置到input中
        dl.on('click', 'dd', function(){
            var othis = $(this);
            if(othis.hasClass(DISABLED)) return false;
            // 把当前行设置为选中行，并设置input的值
            input.val(othis.text());
            othis.addClass(THIS);
            othis.siblings().removeClass(THIS);
            var selItem = options.data[othis.data("index")];
            hideDown(elem);
            if (options.afterSelect && typeof options.afterSelect === 'function') {
                options.afterSelect(selItem);
            }
            return false;
        });
        // 点击其它元素关闭下拉框
        $(document).off('click', hide).on('click', hide);
    };

    // 外部接口
    var typeahead = {
        config: {},
        index: layui[MOD_NAME] ? (layui[MOD_NAME].index + 10000) : 0,

        // 渲染控件
        render: function (options) {
            var inst = new Class(options);
            return thisModule.call(inst);
        },
        // 重载控件
        reload: function (id, options) {
            var that = thisModule.that[id];
            that.reload(options);
            return thisModule.call(that);
        },
        // 注册事件
        on: function (events, callback) {
            return layui.onevent.call(this, MOD_NAME, events, callback);
        }
    };

    // 添加dom节点
    function renderDom(options) {
        // 判断渲染的对象是不是input，如果是，则渲染该input，如果不是input则动态创建input
        if (!options.elem.is('input')) {
            hint.error(MOD_NAME + ' instance with ID \'' + options.id + '\' is not Input element.');
            return;
        }
        options.elem.addClass(CLASS_INPUT);
        // 生成下拉dom
        var cntrDiv = laytpl(TPL_MAIN).render({
            data: options,
        });
        options.elem.after($(cntrDiv));
    };

    // 搜索匹配
    function search(e, options) {
        var elem = options.elem;
        var value = elem.val();
        // 输入框当前值为空时
        if (value === '') {
            // 清空下拉框内容，隐藏下拉框
            elem.next("dl").empty();
            hideDown(elem);
            return false;
        }
        // 输入值不为空时，调用参数中指定的source函数取数据
        if (options.source && typeof options.source === 'function') {
            options.source(value, function (data) {
                // 保存数据
                options.data = data;
                // 处理数据
                process(options, data);
            });
        }
        return false;
    };
    // 对source函数取得的数据进行处理
    function process(options, data) {
        var dl = options.elem.next("dl");
        if (!data || !(data instanceof Array) || !data.length) {
            hideDown(options.elem);
            dl.empty();
            return;
        }
        var dds = [];
        layui.each(data, function (index, item) {
            var dd = $('<dd></dd>');
            if (options.displayText && typeof options.displayText === 'function') {
                dd.text(options.displayText(item));
            } else if (typeof item === 'string') {
                dd.text(item);
            } else {
                dd.text(JSON.stringify(item));
            }
            dd.data("index", index);
            dds.push(dd);
        });
        dl.empty();
        layui.each(dds, function (index, item) {
            dl.append(item);
        });
        showDown(options.elem);
    }
    // 隐藏
    function hide(e, clear) {
        if (!$(e.target).hasClass(CLASS_INPUT) || clear) {
            var elemDown = $('.' + CLASS);
            elemDown.removeClass(CLASS_SELECTED);
        }
    };
    // 显示下拉框
    function showDown(elem) {
        elem.next('dl').addClass(CLASS_SELECTED);
    }
    // 隐藏下拉框
    function hideDown(elem) {
        elem.next('dl').removeClass(CLASS_SELECTED);
    }
    // 定位下拉滚动条
    function followScroll(elem) {
        var dl = elem.next('dl');
        var thisDd = dl.children('dd.' + THIS);
        if (!thisDd[0]) return;
        var posTop = thisDd.position().top;
        var dlHeight = dl.height();
        var ddHeight = thisDd.height();
        // 若选中元素在滚动条不可见底部
        if (posTop > dlHeight) {
            dl.scrollTop(posTop + dl.scrollTop() - dlHeight + ddHeight - 5);
        }
        // 若选择元素在滚动条不可见顶部
        if (posTop < 0) {
            dl.scrollTop(posTop + dl.scrollTop() - 5);
        }
    };

    exports(MOD_NAME, typeahead);
});
