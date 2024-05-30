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
                'children' => [
                    [
                        'id' => '3',
                        'pid' => '2',
                        'name' => '常用工具',
                        'children' => [
                            [
                                'id' => '3',
                                'pid' => '3',
                                'name' => '拾贝3',
                                'desc' => '拾贝，一个专注于个人技术分享的博客。',
                                'link' => 'https://lxshuai.top/'
                            ],
                            [
                                'id' => '4',
                                'pid' => '3',
                                'name' => '拾贝4',
                                'desc' => '拾贝，一个专注于个人技术分享的博客。',
                                'link' => 'https://lxshuai.top/'
                            ]
                        ]
                    ],
                    [
                        'id' => '4',
                        'pid' => '2',
                        'name' => '常用工具2',
                        'children' => [
                            [
                                'id' => '5',
                                'pid' => '4',
                                'name' => '拾贝5',
                                'desc' => '拾贝，一个专注于个人技术分享的博客。',
                                'link' => 'https://lxshuai.top/'
                            ],
                            [
                                'id' => '6',
                                'pid' => '4',
                                'name' => '拾贝6',
                                'desc' => '拾贝，一个专注于个人技术分享的博客。',
                                'link' => 'https://lxshuai.top/'
                            ]
                        ]
                    ]
                ]
            ]
        ];
        foreach ($arr as $key => $val) {
            $child = array_column($val['children'], 'children');
            if (count($child) > 0) {
                $val['type'] = 'categories';
            } else {
                $val['type'] = 'link';
            }
            $arr[$key] = $val;
        }
//        return json($arr);
        return view('index', ['nav' => $arr]);
    }
}
