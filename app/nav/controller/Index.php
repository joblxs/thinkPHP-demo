<?php

namespace app\nav\controller;

use app\BaseController;
use app\nav\model\Categories;
use app\nav\model\Links;
use think\App;

class Index extends BaseController
{
    public function index()
    {
        $cat = Categories::getMenuList(1);
        $link = Links::getAllLinks();
        return view('index', [
            'cat' => $cat,
            'link' => $link
        ]);
    }

    public function api() {
        $homeInfo = [
            'title' => '首页',
            'href'  => 'page/welcome-1.html?t=1',
        ];
        $logoInfo = [
            'title' => '个人导航',
            'image' => 'https://pic.lxshuai.top/i/2024/05/18/6647eeade3ec2.webp',
            'href' => '/'
        ];
        $menuInfo = Categories::getMenuList();
        $systemInit = [
            'homeInfo' => $homeInfo,
            'logoInfo' => $logoInfo,
            'menuInfo' => $menuInfo,
        ];
        return json($systemInit);
    }
}
