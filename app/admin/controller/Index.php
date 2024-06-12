<?php

namespace app\admin\controller;

use app\BaseController;
use app\admin\model\AdminMenu;
use think\facade\Cache;

class Index extends BaseController
{
    public function index()
    {
        return view('index');
    }

    public function menu() {
        // $cacheCate = cache("cate" . session('admin.id'));
        // if (!empty($cacheCate)) {
        //     return json($cacheCate);
        // }

        $homeInfo = [
            'title' => '首页',
            'href'  => '/',
        ];
        $logoInfo = [
            'title' => '后台管理',
            'image' => 'https://pic.lxshuai.top/i/2024/05/18/6647eeade3ec2.webp',
            'href' => 'javascript:;'
        ];
        $menuInfo = AdminMenu::getMenuTree();
        $systemInit = [
            'homeInfo' => $homeInfo,
            'logoInfo' => $logoInfo,
            'menuInfo' => $menuInfo,
        ];
        // 缓存在604800秒之后过期
        // cache("cate" . session('admin.id'), $systemInit, 604800);
        return json($systemInit);
    }

    public function clearCache() {
        Cache::clear();
        return json(['msg' => '清理缓存成功', 'code' => 200]);
    }
}