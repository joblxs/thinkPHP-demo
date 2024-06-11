<?php

namespace app\admin\controller\nav;

use app\BaseController;
use app\admin\model\NavLink;
use hg\apidoc\annotation as Apidoc;

#[Apidoc\Title("导航链接管理")]
class Link extends BaseController
{
    public function index(){
        return view('index');
    }


    public function apiIndex() {
        $linkList = NavLink::getLinkList();

        return json(['code' => 0, 'data' => $linkList]);
    }
}
