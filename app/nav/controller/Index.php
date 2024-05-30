<?php

namespace app\nav\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        $cat = [
            [
                'id' => 1,
                'pid' => 0,
                'name' => '个人站点',
            ],
            [
                'id' => 2,
                'pid' => 0,
                'name' => '工具箱',
                'children' => [
                    [
                        'id' => 3,
                        'pid' => 2,
                        'name' => '常用工具'
                    ],
                    [
                        'id' => 4,
                        'pid' => 2,
                        'name' => '常用工具2'
                    ]
                ]
            ]
        ];
        $link = [
            1 => [
                [
                    'id' => 1,
                    'name' => '拾贝',
                    'desc' => '拾贝，一个专注于个人技术分享的博客。',
                    'link' => 'https://lxshuai.top/'
                ],
                [
                    'id' => 2,
                    'name' => '拾贝2',
                    'desc' => '拾贝，一个专注于个人技术分享的博客。',
                    'link' => 'https://lxshuai.top/'
                ]
            ],
            2 => [
                [
                    'id' => 3,
                    'name' => '拾贝3',
                    'desc' => '拾贝，一个专注于个人技术分享的博客。',
                    'link' => 'https://lxshuai.top/'
                ],
                [
                    'id' => 4,
                    'name' => '拾贝4',
                    'desc' => '拾贝，一个专注于个人技术分享的博客。',
                    'link' => 'https://lxshuai.top/'
                ]
            ],
            4 => [
                [
                    'id' => 5,
                    'name' => '拾贝5',
                    'desc' => '拾贝，一个专注于个人技术分享的博客。',
                    'link' => 'https://lxshuai.top/'
                ],
                [
                    'id' => 6,
                    'name' => '拾贝6',
                    'desc' => '拾贝，一个专注于个人技术分享的博客。',
                    'link' => 'https://lxshuai.top/'
                ]
            ],
        ];
        return view('index', [
            'cat' => $cat,
            'link' => $link
        ]);
    }
}
