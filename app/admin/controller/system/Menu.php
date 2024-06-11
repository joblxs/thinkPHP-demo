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

    public function add() {
        $pidMenuList = AdminMenu::getPidMenuList();
        $pid = $this->request->get('pid');
        return view('add', [
            'pidMenuList' => $pidMenuList,
            'pid' => $pid
        ]);
    }

    public function edit() {
        $get = $this->request->get();

        $AdminMenu = new AdminMenu();
        $row = $AdminMenu->find($get['id']);
        $pidMenuList = AdminMenu::getPidMenuList();
        return view('edit', [
            'id'          => $get['id'],
            'pidMenuList' => $pidMenuList,
            'row' => $row
        ]);
    }

    #[
        Apidoc\Title("菜单列表"),
        Apidoc\Tag("系统管理"),
        Apidoc\Method("GET"),
        Apidoc\Url("/admin/system.menu/apiIndex"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Returned("json",type: "json",desc: "json"),
    ]
    public function apiIndex() {
        $menuInfo = AdminMenu::getMenuList();

        return json(['code' => 200, 'data' => $menuInfo]);
    }

    #[
        Apidoc\Title("添加菜单"),
        Apidoc\Tag("系统管理"),
        Apidoc\Method("POST"),
        Apidoc\Url("/admin/system.menu/apiAdd"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Returned("json",type: "json",desc: "json"),
    ]
    public function apiAdd() {
        $post = $this->request->post();
        $rule = [
            'pid|上级菜单'   => 'require',
            'title|菜单名称' => 'require',
            'icon|菜单图标'  => 'require',
        ];
        $this->validate($post, $rule);
        try {
            $AdminMenu = new AdminMenu();
            $save = $AdminMenu->save($post);
        } catch (\Exception $e) {
            return json(['code' => 201, 'msg' => '添加失败']);
        }
        if ($save) {
            // TriggerService::updateMenu();
            return json(['code' => 200, 'msg' => '添加成功']);
        } else {
            return json(['code' => 201, 'msg' => '添加失败']);
        }
    }


    public function apiEdit() {
        $post = $this->request->post();
        $rule = [
            'pid|上级菜单'   => 'require',
            'title|菜单名称' => 'require',
            'icon|菜单图标'  => 'require',
        ];
        $this->validate($post, $rule);
        try {
            $AdminMenu = new AdminMenu();
            $save = $AdminMenu->update($post);
        } catch (\Exception $e) {
            var_dump($e);
            return json(['code' => 201, 'msg' => '修改失败']);
        }
        if ($save) {
            // TriggerService::updateMenu();
            return json(['code' => 200, 'msg' => '修改成功']);
        } else {
            return json(['code' => 201, 'msg' => '修改失败']);
        }
    }
}