<?php

namespace app\admin\controller\system;

use app\BaseController;
use app\admin\model\AdminMenu;
use hg\apidoc\annotation as Apidoc;

#[Apidoc\Title("系统管理")]
class Menu extends BaseController
{

    public function index() {
        return view('menu');
    }
    #[
        Apidoc\Title("菜单列表"),
        Apidoc\Tag("系统管理"),
        Apidoc\Method("GET"),
        Apidoc\Url("/admin/system.menu/apiIndex"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Returned("token",type: "string",desc: "token"),
    ]
    public function apiIndex() {
        $menuInfo = AdminMenu::getMenuList();

        return json(['code' => 0, 'data' => $menuInfo]);
    }
}