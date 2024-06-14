<?php
namespace app\api\controller;

use app\BaseController;
use think\Request;
use hg\apidoc\exception\ErrorException;
use hg\apidoc\annotation as Apidoc;
use yzh52521\EasyHttp\Http;

// 必须的

#[Apidoc\Title("兰空图床")]
class Lsky extends BaseController
{
    // 接口URL
    private static $apiUrl = "https://pic.lxshuai.top/api/v1";
    private static $token = "Bearer 5|mkeqp2oAHGIQIX92X8FAgvuxjfK8Ih3Jhu6cRTJc";

    #[
        Apidoc\Title("获取token"),
        Apidoc\Tag("兰空"),
        Apidoc\Method("GET"),
        Apidoc\Url("/api/lsky/getTokens"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Query(name:"email",type: "string",require: true,desc: "邮箱",mock:"@email"),
        Apidoc\Query(name:"password",type: "string",require: true,desc: "密码",mock:"@string(5)"),
        Apidoc\Returned("token",type: "string",desc: "token"),
    ]
    public function getTokens(Request $request)
    {
        $email = $request->get('email', '');
        $password = $request->get('password', '');
        try {
            $response = Http::asJson()->post(self::$apiUrl . "/tokens", ['email' => $email, 'password' => $password]);

            if ($response->status() == 200) {
                return json($response->json()->data);
            }
            return json($response->status());
        } catch (ErrorException $e) {
            var_dump($e);
            return $e;
        }
    }

    #[
        Apidoc\Title("相册列表"),
        Apidoc\Tag("兰空"),
        Apidoc\Method("GET"),
        Apidoc\Url("/api/lsky/getAlbums"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Query(name:"page",type: "int",require: true,desc: "页码",mock:"1"),
        Apidoc\Query(name:"order",type: "string",require: true,desc: "排序方式，newest=最新，earliest=最早，most=图片最多，least=图片最少",mock:"newest"),
        Apidoc\Query(name:"keyword",type: "string",require: false,desc: "筛选关键字",mock:""),
        Apidoc\Returned("data",type: "array",desc: "data"),
    ]
    public function getAlbums(Request $request) {
        $page = $request->get('page', 1);
        $order = $request->get('order', 'newest');
        $keyword = $request->get('keyword', '');
        try {
            $response = Http::asJson()->withHeaders([
                'Authorization' => self::$token
            ])->get(self::$apiUrl . "/albums", ['page' => $page, 'order' => $order, 'keyword' => $keyword]);

            if ($response->status() == 200) {
                return json($response->json()->data->data);
            }
            return json($response->status());
        } catch (ErrorException $e) {
            var_dump($e);
            return $e;
        }
    }

    #[
        Apidoc\Title("图片列表"),
        Apidoc\Tag("兰空"),
        Apidoc\Method("GET"),
        Apidoc\Url("/api/lsky/getImages"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Query(name:"page",type: "int",require: true,desc: "页码",mock:"1"),
        Apidoc\Query(name:"order",type: "string",require: true,desc: "排序方式，newest=最新，earliest=最早，utmost=最大，least=最小",mock:"newest"),
        Apidoc\Query(name:"permission",type: "string",require: true,desc: "权限，public=公开的，private=私有的",mock:"public"),
        Apidoc\Query(name:"album_id",type: "int",require: true,desc: "相册 ID"),
        Apidoc\Query(name:"keyword",type: "string",require: false,desc: "筛选关键字"),
        Apidoc\Returned("data",type: "array",desc: "data"),
    ]
    public function getImages(Request $request) {
        $page = $request->get('page', 1);
        $order = $request->get('order', 'newest');
        $permission = $request->get('permission', '');
        $album_id = $request->get('album_id', '');
        $keyword = $request->get('keyword', '');
        try {
            $response = Http::asJson()->withHeaders([
                'Authorization' => self::$token
            ])->get(self::$apiUrl . "/images", [
                'page' => $page, 'order' => $order, 'permission' => $permission, 'album_id' => $album_id, 'keyword' => $keyword
            ]);

            if ($response->status() == 200) {
                return json($response->json()->data->data);
            }
            return json($response->status());
        } catch (ErrorException $e) {
            var_dump($e);
            return $e;
        }
    }

    #[
        Apidoc\Title("返回一张随机图片"),
        Apidoc\Tag("兰空"),
        Apidoc\Method("GET"),
        Apidoc\Url("/api/lsky/randomImages"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Query(name:"category",type: "string",require: true,desc: "类别：风景、汽车、星空、手机壁纸、24节气、动漫"),
        Apidoc\Returned("data",type: "array",desc: "data"),
    ]
    public function randomImages(Request $request) {
        $category = $request->get('category', '');
        $size = $request->get('size', 'url');
        $randomImage = @\app\api\model\Lsky::randomImages($category, $size);
        return redirect($randomImage);
    }

    #[
        Apidoc\Title("返回一张随机图片（可指定尺寸）"),
        Apidoc\Tag("兰空"),
        Apidoc\Method("GET"),
        Apidoc\Url("/api/lsky/randomSizeImages"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Query(name:"category",type: "string",require: true,desc: "类别：风景、汽车、星空、手机壁纸、24节气、动漫"),
        Apidoc\Query(name:"width",type: "int",require: true,desc: "图片宽度（默认300）"),
        Apidoc\Query(name:"height",type: "int",require: false,desc: "图片高度（不传自行计算宽高比）"),
        Apidoc\Returned("data",type: "array",desc: "data"),
    ]
    public function randomSizeImages(Request $request) {
        $category = $request->get('category', '');
        $size = $request->get('size', 'url');
        $width = $request->get('width', 300);
        $height = $request->get('height', 0);
        $album = [
            ["id" => 8, "name" => "风景"],
            ["id" => 7, "name" => "汽车"],
            ["id" => 6, "name" => "星空"],
            ["id" => 5, "name" => "手机壁纸"],
            ["id" => 4, "name" => "24节气"],
            ["id" => 3, "name" => "动漫"]
        ];
        $album_id = 0;
        if (!empty($category)) {
            foreach ($album as $item) {
                if ($item["name"] == $category) {
                    $album_id = $item["id"];
                }
            }
        }

        if ($album_id == 0) {
            // 使用array_rand随机获取一个数组的键
            $random_key = array_rand($album);
            // 使用随机键获取对应的id
            $album_id = $album[$random_key]['id'];
        }
        $cache_key = "img_" . $size . $album_id;
        try {
            $images = cache($cache_key);
            if (empty($images)) {
                $response = Http::asJson()->withHeaders([
                    'Authorization' => self::$token
                ])->get(self::$apiUrl . "/images", [
                    'page' => 1, 'order' => 'newest', 'album_id' => $album_id
                ]);
                $imageArr = [];
                if ($response->status() == 200) {
                    $data = $response->json()->data->data;
                    foreach ($data as $key => $value) {
                        array_push($imageArr, $value->links->url);
                    }
                    $images = \app\api\model\Lsky::setCatch($cache_key, json_encode($imageArr));
                }
            }
            $imageArr = json_decode($images);
            if (count($imageArr) > 0) {
                $randomImage = $imageArr[array_rand($imageArr)];
                ob_start(); // 开始输出缓冲
                \app\api\model\Lsky::sizeImages($randomImage, $width, $height);
                $output = ob_get_clean(); // 获取输出缓冲并清空
                echo $output; // 输出缓冲内容（如果有的话）
            }

            return json($response->status());
        } catch (ErrorException $e) {
            var_dump($e);
            return $e;
        }
    }

    #[
        Apidoc\Title("裁剪指定图片尺寸"),
        Apidoc\Tag("兰空"),
        Apidoc\Method("GET"),
        Apidoc\Url("/api/lsky/tailorImages"),
        Apidoc\Header(name:"Content-Type",type: "string",require: true,desc: "Content-Type 编码请求",mock: "application/json"),
        Apidoc\Query(name:"url",type: "string",require: true,desc: "图片地址"),
        Apidoc\Query(name:"width",type: "int",require: true,desc: "图片宽度"),
        Apidoc\Query(name:"height",type: "int",require: false,desc: "图片高度（不传自行计算宽高比）"),
        Apidoc\Returned("data",type: "array",desc: "data"),
    ]
    public function tailorImages(Request $request) {
        $url = $request->get('url', '');
        $width = $request->get('width', 30);
        $height = $request->get('height', 0);
        try {
            ob_start(); // 开始输出缓冲
            \app\api\model\Lsky::sizeImages($url, $width, $height);
            $output = ob_get_clean(); // 获取输出缓冲并清空
            echo $output; // 输出缓冲内容（如果有的话）
        } catch (ErrorException $e) {
            var_dump($e);
            return $e;
        }
    }
}