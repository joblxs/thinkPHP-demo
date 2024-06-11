<?php

return [

    // 不需要验证登录的控制器
    'no_login_controller' => [
        'auth',
    ],

    // 不需要验证登录的节点
    'no_login_node'       => [
        'auth/apiLogin',
        'auth/logOut',
    ],

    // 不需要验证权限的控制器
    'no_auth_controller'  => [
        'login',
        'index',
    ],

    // 不需要验证权限的节点
    'no_auth_node'        => [
        'auth/apiLogin',
        'auth/logOut',
    ],
];