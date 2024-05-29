<?php

namespace app\nav\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        $arr = [
            [
                'id' => '1',
                'pid' => '0',
                'name' => '个人站点',
                'type' => 'categories',
                'children' => [
                    [
                        'id' => '1',
                        'pid' => '1',
                        'name' => '拾贝',
                        'desc' => '拾贝，一个专注于个人技术分享的博客。',
                        'link' => 'https://lxshuai.top/'
                    ],
                    [
                        'id' => '2',
                        'pid' => '1',
                        'name' => '拾贝2',
                        'desc' => '拾贝，一个专注于个人技术分享的博客。',
                        'link' => 'https://lxshuai.top/'
                    ]
                ]
            ],
            [
                'id' => '2',
                'pid' => '0',
                'name' => '工具箱',
                'type' => 'categories',
                'children' => [
                    [
                        'id' => '3',
                        'pid' => '2',
                        'name' => '常用工具',
                        'type' => 'categories',
                        'children' => [
                            [
                                'id' => '3',
                                'pid' => '3',
                                'name' => '拾贝',
                                'desc' => '拾贝，一个专注于个人技术分享的博客。',
                                'link' => 'https://lxshuai.top/'
                            ],
                            [
                                'id' => '4',
                                'pid' => '3',
                                'name' => '拾贝2',
                                'desc' => '拾贝，一个专注于个人技术分享的博客。',
                                'link' => 'https://lxshuai.top/'
                            ]
                        ]
                    ],
                    [
                        'id' => '4',
                        'pid' => '2',
                        'name' => '常用工具2',
                        'type' => 'categories',
                        'children' => [
                            [
                                'id' => '5',
                                'pid' => '4',
                                'name' => '拾贝',
                                'desc' => '拾贝，一个专注于个人技术分享的博客。',
                                'link' => 'https://lxshuai.top/'
                            ],
                            [
                                'id' => '6',
                                'pid' => '4',
                                'name' => '拾贝2',
                                'desc' => '拾贝，一个专注于个人技术分享的博客。',
                                'link' => 'https://lxshuai.top/'
                            ]
                        ]
                    ]
                ]
            ]
        ];
        return view('index', ['nav'=>$arr]);
    }
}
