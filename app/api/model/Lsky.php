<?php
namespace app\api\model;

use think\Model;
use yzh52521\EasyHttp\Http;
use hg\apidoc\exception\ErrorException;

class Lsky extends Model
{
    // 接口URL
    private static $apiUrl = "https://pic.lxshuai.top/api/v1";
    private static $token = "Bearer 5|mkeqp2oAHGIQIX92X8FAgvuxjfK8Ih3Jhu6cRTJc";

    public static function setCatch ($album_id, $imageArr) {
        // 缓存在604800秒之后过期
        cache("img".$album_id, $imageArr, 604800);
        return $imageArr;
    }

    public static function randomImages ($category) {
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

        try {
            $images = cache("img".$album_id);
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
                    $images = self::setCatch($album_id, json_encode($imageArr));
                }
            }
            $imageArr = json_decode($images);
            if (count($imageArr) > 0) {
                $randomImage = $imageArr[array_rand($imageArr)];
                return $randomImage;
            }
            return $response->status();
        } catch (ErrorException $e) {
            var_dump($e);
            return $e;
        }
    }

    /**
     * 返回指定尺寸图片
     * @param $image_url
     * @param $new_width
     * @param $new_height
     * @return void
     */
    public static function sizeImages($image_url, $new_width = 200, $new_height = 0) {
        if (empty($image_url)) {
            header('HTTP/1.1 500 Internal Server Error');
            echo "Error loading image.";
            exit; // 退出脚本
        }
        $response = Http::asJson()->get($image_url);
        // 确保$response是二进制数据
        if ($response->successful()) {
            $image_data = $response->body();
            $image = imagecreatefromstring($image_data);
            // 原始图片尺寸
            $original_width = imagesx($image);
            $original_height = imagesy($image);
            // 新图片尺寸
            if ($new_height == 0) {
//                $new_height = ($original_height / $original_width) * $new_width; // 保持宽高比
                $new_height = (int)($original_height * $new_width / $original_width); // 保持宽高比
            }
            // 创建一个新图片资源，用于调整尺寸
            $resized_image = imagecreatetruecolor($new_width, $new_height);
            // 调整图片尺寸
            imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
            // 输出调整尺寸后的图片
            header('Content-Type: image/webp');
            imagewebp($resized_image);
            // 释放资源
            imagedestroy($image);
            imagedestroy($resized_image);
            exit; // 退出脚本，防止进一步执行
        } else {
            // 处理错误情况，例如返回一个错误图片或显示错误消息
            header('HTTP/1.1 500 Internal Server Error');
            echo "Error loading image.";
            exit; // 退出脚本
        }
    }
}