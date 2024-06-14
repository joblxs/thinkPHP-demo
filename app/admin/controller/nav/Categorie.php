<?php

namespace app\admin\controller\nav;

use app\BaseController;
use app\admin\model\NavCategorie;
use hg\apidoc\annotation as Apidoc;

#[Apidoc\Title("导航分类管理")]
class Categorie extends BaseController
{
    public function index(){
        return view('index');
    }

    public function add() {
        $pidCateList = NavCategorie::getPidCateList();
        $pid = $this->request->get('pid');
        return view('add', [
            'pidCateList' => $pidCateList,
            'pid' => $pid
        ]);
    }

    public function edit() {
        $get = $this->request->get();

        $NavCategorie = new NavCategorie();
        $row = $NavCategorie->find($get['id']);
        $pidCateList = NavCategorie::getPidCateList();
        return view('edit', [
            'id'          => $get['id'],
            'pidCateList' => $pidCateList,
            'row' => $row
        ]);
    }

    #[
        Apidoc\Title("分类列表"),
        Apidoc\Tag("导航管理"),
        Apidoc\Method("GET"),
        Apidoc\Url("/admin/nav.categorie/apiIndex"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Returned("token",type: "string",desc: "token"),
    ]
    public function apiIndex() {
        $categorieInfo = NavCategorie::getCateList();

        return json(['code' => 200, 'data' => $categorieInfo]);
    }

    #[
        Apidoc\Title("添加分类"),
        Apidoc\Tag("导航管理"),
        Apidoc\Method("POST"),
        Apidoc\Url("/admin/nav.categorie/apiAdd"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Returned("json",type: "json",desc: "json"),
    ]
    public function apiAdd() {
        $post = $this->request->post();
        $rule = [
            'pid|上级分类'   => 'require',
            'title|分类名称' => 'require',
            'icon|分类图标'  => 'require',
        ];
        $this->validate($post, $rule);
        try {
            $NavCategorie = new NavCategorie();
            $save = $NavCategorie->save($post);
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


    #[
        Apidoc\Title("更新分类"),
        Apidoc\Tag("导航管理"),
        Apidoc\Method("POST"),
        Apidoc\Url("/admin/nav.categorie/apiEdit"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Returned("json",type: "json",desc: "json"),
    ]
    public function apiEdit() {
        $post = $this->request->post();
        $rule = [
            'pid|上级分类'   => 'require',
            'title|分类名称' => 'require',
            'icon|分类图标'  => 'require',
        ];
        $this->validate($post, $rule);
        try {
            $NavCategorie = new NavCategorie();
            $save = $NavCategorie->update($post);
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

    public function status() {
        $post = $this->request->post();
        $rule = [
            'id|ID'   => 'require',
            'status|状态' => 'require'
        ];
        $this->validate($post, $rule);
        try {
            $NavCategorie = new NavCategorie();
            $save = $NavCategorie->update($post);
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