<?php

namespace app\admin\controller;

use app\BaseController;
use hg\apidoc\annotation as Apidoc;
use app\admin\common\JwtUtil;
use app\admin\model\AdminUser;

#[Apidoc\Title("登录")]
class Auth extends BaseController
{
    /**
     * 初始化方法
     */
    public function initialize()
    {
        parent::initialize();
        $action = $this->request->action();
        if (!empty(session('admin')) && !in_array($action, ['apiLoginOut'])) {
            return redirect('/admin');
        }
    }

    public function login()
    {
        $loginOptions = ['login-1', 'login-2', 'login-3'];
        $randomLogin = $loginOptions[array_rand($loginOptions)];
        return view($randomLogin);
    }

    #[
        Apidoc\Title("登录"),
        Apidoc\Tag("后台管理"),
        Apidoc\Method("POST"),
        Apidoc\Url("/admin/auth/apiLogin"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Param(name:"username",type: "string",require: true,desc: "账号"),
        Apidoc\Param(name:"password",type: "string",require: true,desc: "密码"),
        Apidoc\Returned("data",type: "array"),
    ]
    public function apiLogin()
    {
        $post = $this->request->post();
        $rule = [
            'username|用户名'      => 'require',
            'password|密码'       => 'require',
        ];
        $this->validate($post, $rule);
        $admin = AdminUser::getAdmin($post['username']);
        if (empty($admin)) {
            return json([
                'msg' => '账号不存在', 'code' => 401
            ]);
        }
        if (password($post['password']) != $admin->password) {
            return json([
                'msg' => '密码错误', 'code' => 401
            ]);
        }
        if ($admin->status == 1) {
            return json([
                'msg' => '账号已被禁用', 'code' => 401
            ]);
        }
        // 登录次数+1
        AdminUser::loginNum($post['username']);


        $admin->login_num += 1;
        $admin = $admin->toArray();
        unset($admin['password']);
        unset($admin['create_at']);
        unset($admin['update_at']);
        unset($admin['delete_at']);

        $jwtUtil = new JwtUtil();
        $token = $jwtUtil->createToken($admin['id']);
        $admin['expire_time'] = time() + 86400;
        $admin['token'] = $token;
        session('admin', $admin);
        return json([
            'data' => $admin, 'code' => 200
        ]);
    }

    #[
        Apidoc\Title("退出登录"),
        Apidoc\Tag("后台管理"),
        Apidoc\Method("POST"),
        Apidoc\Url("/admin/auth/logOut"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
    ]
    public function logOut()
    {
        session('admin', null);
        return json([
            'msg' => '退出登录成功', 'code' => 200
        ]);
    }
}