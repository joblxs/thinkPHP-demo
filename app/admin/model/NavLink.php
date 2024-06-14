<?php
namespace app\admin\model;

use GuzzleHttp\Exception\RequestException;
use think\Model;
use QL\QueryList;

class NavLink extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'nav_link';
    // 设置时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';
    protected $deleteTime = 'delete_at';

    public static function getLinkList($limit) {
        $list = self::alias('nl')
            ->field('nl.*, nc.title, nc.icon')
            ->where('nl.is_delete', 0)
            ->join('nav_categorie nc', 'nl.cat_id = nc.id')
            ->order(['nl.sort' => 'desc', 'nl.id' => 'asc'])
            ->paginate($limit);
        return $list;
    }

    /**
     * 通过url获取网站标题等信息
     * @param $url
     * @return array|string[]
     */
    public static function getSiteInfo($url) {
        // 使用QueryList获取网站标题、描述、关键词、图标
        try {
            $html = QueryList::get($url, null, [
                'timeout' => 30, // 设置超时时间，单位为秒
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36',
                ],
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false, // 禁用 SSL 证书验证
                    CURLOPT_SSL_VERIFYHOST => false, // 禁用 SSL 主机名验证
                    CURLOPT_FOLLOWLOCATION => true, // 允许重定向
                ],
            ])->getHtml();
        } catch (RequestException $e) {
            return ['title' => '', 'description' => '', 'keywords' => '', 'icon' => ''];
        }

        // 解析HTML，获取网站标题、描述、关键词、图标
        $title = QueryList::html($html)->find('title')->text();
        $description = QueryList::html($html)->find('meta[name="description"]')->attr('content');
        $keywords = QueryList::html($html)->find('meta[name="keywords"]')->attr('content');
        $icon = QueryList::html($html)->find('link[rel="shortcut icon"]')->attr('href');
        if (empty($icon)) {
            $icon = QueryList::html($html)->find('link[rel="icon"]')->attr('href');
        }
        if ($icon) {
            // 判断是否是绝对路径，如果不是则添加网站域名
            if (strpos($icon, 'http') !== 0 && strpos($icon, '//') !== 0) {
                $icon = $url . '/' . ltrim($icon, '/');
            }
        }

        return ['title' => $title, 'description' => $description, 'keywords' => $keywords, 'icon' => $icon];
    }

    /**
     * @param $url
     * @return string
     * 保存图标至本地
     */
    public static function saveIcon($url) {
        // 解析 URL 获取域名
        $parsedUrl = parse_url($url);
        $domain = isset($parsedUrl['host']) ? $parsedUrl['host'] : 'unknown';
        // 清理域名，移除非字母数字字符
        $cleanDomain = preg_replace('/[^a-zA-Z0-9\-]/', '_', $domain);
        // 从 URL 中解析文件扩展名
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        // 如果没有找到扩展名，默认为 'ico'
        if (!$extension) {
            $extension = 'ico';
        }
        // 生成随机文件名
        $fileName = $cleanDomain . '.' . $extension;
        // 下载文件
        $iconData = file_get_contents($url);
        // 保存文件
        $savePath = 'resource/img/icon/' . $fileName; // 替换为你想保存的路径
        file_put_contents($savePath, $iconData);

        return '/' . $savePath;
    }
}
