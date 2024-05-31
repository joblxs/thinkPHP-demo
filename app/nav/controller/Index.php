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
        $cat = Categories::getAllCategoriesTree();
        // 将树状结构转换为JSON格式输出
        $link = Links::getAllLinks();
        return view('index', [
            'cat' => $cat,
            'link' => $link
        ]);
    }
}
