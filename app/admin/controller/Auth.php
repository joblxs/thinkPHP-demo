<?php

namespace app\admin\controller;

use app\BaseController;
use think\Request;
use hg\apidoc\annotation as Apidoc;
use app\admin\common\JwtUtil;

#[Apidoc\Title("登录管理")]
class Auth extends BaseController
{
    #[
        Apidoc\Title("登录"),
        Apidoc\Tag("后台管理"),
        Apidoc\Method("POST"),
        Apidoc\Url("/admin/auth/login"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Param(name:"username",type: "string",require: true,desc: "账号",mock:"admin"),
        Apidoc\Param(name:"password",type: "string",require: true,desc: "密码",mock:"123456"),
        Apidoc\Returned("token",type: "string",desc: "token"),
    ]
    public function login(Request $request)
    {
        $username = $request->post('username');
        $password = $request->post('password');
        // 这里应该添加验证用户名和密码的逻辑
        // 假设用户名是admin，密码是123456

        if ($username == 'admin' && $password == '123456') {
            $jwtUtil = new JwtUtil();
            $token = $jwtUtil->createToken(1); // 假设用户ID为1

            return json(['token' => $token]);
        } else {
            return json(['error' => '用户名或密码错误'], 401);
        }
    }
}