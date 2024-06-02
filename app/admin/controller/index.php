<?php

namespace app\admin\controller;

use app\BaseController;
use app\admin\model\AdminMenu;

class Index extends BaseController
{
    public function index()
    {
        if ($this->request->isAjax()) {
            var_dump(111);
        }
        return view('index');
    }

    public function menu() {
      $homeInfo = [
        'title' => '首页',
        'href'  => '/',
    ];
    $logoInfo = [
        'title' => '后台管理',
        'image' => 'https://pic.lxshuai.top/i/2024/05/18/6647eeade3ec2.webp',
        'href' => '/'
    ];
    $menuInfo = AdminMenu::getMenuTree();
    $systemInit = [
        'homeInfo' => $homeInfo,
        'logoInfo' => $logoInfo,
        'menuInfo' => $menuInfo,
    ];
    return json($systemInit);
    }
}