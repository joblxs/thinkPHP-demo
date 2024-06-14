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
        $cat = Categories::getMenuList(0);
        $link = Links::getAllLinks();
        return view('index', [
            'cat' => $cat,
            'link' => $link
        ]);
    }

    public function api() {
        $homeInfo = [
            'title' => '首页',
            'href'  => '/',
        ];
        $logoInfo = [
            'title' => '拾贝导航',
            'image' => 'https://pic.lxshuai.top/i/2024/05/18/6647eeade3ec2.webp',
            'href' => 'javascript:;'
        ];
        $menuInfo = Categories::getMenuList();
        $systemInit = [
            'homeInfo' => $homeInfo,
            'logoInfo' => $logoInfo,
            'menuInfo' => $menuInfo,
        ];
        return json($systemInit);
    }

    public function clickLink() {
        $id = $this->request->get('id', 0);

        if ($id == 0) {
            return json(['msg' => '链接错误', 'code' => 400]);
        }
        $link = Links::getLink($id);
        if (empty($link)) {
            return json(['msg' => '链接不存在', 'code' => 400]);
        }

        if ($link->is_pass == 1) {
            $password = $this->request->get('password', '');
            if (empty($password)) {
                return json(['msg' => '请输入密令', 'code' => 400]);
            }
            if ($password != $link->password) {
                return json(['msg' => '密令错误', 'code' => 400]);
            }
        }
        // 点击次数+1
        Links::clickNum($id);

        $link = $link->toArray();
        unset($link['password']);
        return json(['code' => 200, 'data' => $link]);
    }
}
