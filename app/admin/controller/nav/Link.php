<?php

namespace app\admin\controller\nav;

use app\admin\model\NavCategorie;
use app\BaseController;
use app\admin\model\NavLink;
use hg\apidoc\annotation as Apidoc;

#[Apidoc\Title("导航链接管理")]
class Link extends BaseController
{
    public function index(){
        return view('index');
    }

    public function add() {
        $pidCateList = NavCategorie::getCatesList();
        $cid = $this->request->get('cid');
        return view('add', [
            'pidCateList' => $pidCateList,
            'cid' => $cid
        ]);
    }

    public function edit() {
        $get = $this->request->get();

        $NavLink = new NavLink();
        $row = $NavLink->find($get['id']);
        if (!empty($row['link_img'])) {
            $row['links_img'] = $row['link_img'];
        }

        $pidCateList = NavCategorie::getCatesList();
        return view('edit', [
            'id'          => $get['id'],
            'pidCateList' => $pidCateList,
            'row' => $row
        ]);
    }


    #[
        Apidoc\Title("链接列表"),
        Apidoc\Tag("导航管理"),
        Apidoc\Method("GET"),
        Apidoc\Url("/admin/nav.link/apiIndex"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Returned("token",type: "string",desc: "token"),
    ]
    public function apiIndex() {
        $limit = $this->request->get('limit', 15);

        $linkList = NavLink::getLinkList($limit);
        $total = $linkList->total();
        $data = $linkList->items();
        foreach ($data as $item) {
            if (!empty($item['link_img'])) {
                $item['link_img'] = $item['link_img'];
            }
        }

        return json(['code' => 0, 'data' => $data, 'count' => $total]);
    }

    #[
        Apidoc\Title("添加链接"),
        Apidoc\Tag("导航管理"),
        Apidoc\Method("POST"),
        Apidoc\Url("/admin/nav.link/apiAdd"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Returned("json",type: "json",desc: "json"),
    ]
    public function apiAdd() {
        $post = $this->request->post();
        $rule = [
            'cat_id|所属分类'   => 'require',
            'link|链接' => 'require',
            'link_name|链接名称'  => 'require',
        ];
        $this->validate($post, $rule);
        try {
            $parsedUrl = parse_url($post['link_img']);
            $domain = isset($parsedUrl['host']) ? $parsedUrl['host'] : 'unknown';
            if ($domain != 'unknown' && $domain != 'pic.lxshuai.top') {
                $post['link_img'] = NavLink::saveIcon($post['link_img']);
            }
            $NavLink = new NavLink();
            $save = $NavLink->save($post);
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
        Apidoc\Title("更新链接"),
        Apidoc\Tag("导航管理"),
        Apidoc\Method("POST"),
        Apidoc\Url("/admin/nav.link/apiEdit"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Returned("json",type: "json",desc: "json"),
    ]
    public function apiEdit() {
        $post = $this->request->post();
        $rule = [
            'cat_id|所属分类'   => 'require',
            'link|链接' => 'require',
            'link_name|链接名称'  => 'require',
        ];
        $this->validate($post, $rule);

        try {
            $parsedUrl = parse_url($post['link_img']);
            $domain = isset($parsedUrl['host']) ? $parsedUrl['host'] : 'unknown';
            
            if ($domain != 'unknown' && $domain != 'pic.lxshuai.top') {
                $post['link_img'] = NavLink::saveIcon($post['link_img']);
            }
            $NavLink = new NavLink();
            $save = $NavLink->update($post);
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
            $NavLink = new NavLink();
            $save = $NavLink->update($post);
        } catch (\Exception $e) {
            return json(['code' => 201, 'msg' => '修改失败']);
        }
        if ($save) {
            // TriggerService::updateMenu();
            return json(['code' => 200, 'msg' => '修改成功']);
        } else {
            return json(['code' => 201, 'msg' => '修改失败']);
        }
    }

    public function password() {
        $post = $this->request->post();
        $rule = [
            'id|ID'   => 'require',
            'is_pass|密码状态' => 'require'
        ];
        $this->validate($post, $rule);
        try {
            $NavLink = new NavLink();
            $save = $NavLink->update($post);
        } catch (\Exception $e) {
            return json(['code' => 201, 'msg' => '修改失败']);
        }
        if ($save) {
            // TriggerService::updateMenu();
            return json(['code' => 200, 'msg' => '修改成功']);
        } else {
            return json(['code' => 201, 'msg' => '修改失败']);
        }
    }

    public function getSiteInfo() {
        $url = $this->request->get('url');
        $info = NavLink::getSiteInfo($url);
        return json(['code' => 0, 'info' => $info]);
    }
}
