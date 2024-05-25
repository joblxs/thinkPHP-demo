<?php

namespace app\index\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return view('index');
    }

    public function index1()
    {
        $html = "<div style='width:100%;height:100%;font-size: 20px;text-align: center;'>
            欢迎来到api管理系统，<a href='/apidoc'>点击跳转</a>
        </div>";
        return $html;
    }
}
