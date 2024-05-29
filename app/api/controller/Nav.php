<?php
namespace app\api\controller;

use app\BaseController;
use think\Request;
use hg\apidoc\exception\ErrorException;
use hg\apidoc\annotation as Apidoc;
use yzh52521\EasyHttp\Http;

// 必须的

#[Apidoc\Title("个人导航")]
class Nav extends BaseController
{
    #[
        Apidoc\Title("获取导航"),
        Apidoc\Tag("导航"),
        Apidoc\Method("GET"),
        Apidoc\Url("/api/nav/getCate"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Returned("token",type: "string",desc: "token"),
    ]
    public function getCate(Request $request)
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
        return json($arr);
    }
}