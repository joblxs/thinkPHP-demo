<?php

namespace app\admin\controller\nav;

use app\BaseController;
use app\nav\model\Categories;
use hg\apidoc\annotation as Apidoc;

#[Apidoc\Title("导航分类管理")]
class Categorie extends BaseController
{
    #[
        Apidoc\Title("分类列表"),
        Apidoc\Tag("导航管理"),
        Apidoc\Method("GET"),
        Apidoc\Url("/admin/nav.categorie/index"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Returned("token",type: "string",desc: "token"),
    ]
    public function index() {
        $menuInfo = Categories::getMenuList();

        return $menuInfo;
    }
}