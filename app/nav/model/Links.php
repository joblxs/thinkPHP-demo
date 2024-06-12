<?php
namespace app\nav\model;

use think\Model;

class Links extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'nav_link';
    // 设置时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    public static function getAllLinks()
    {
        $links = self::order(['sort' => 'desc', 'id' => 'asc'])->select()->toArray();
        $newFormat = [];
        foreach ($links as $item) {
            if (empty($item['link_img'])) {
                $item['link_img'] = 'https://lxshuai.top/api/lsky/randomImages?'.mt_rand(1, 100);
            }
            if (empty($item['link_desc'])) {
                $item['link_img'] = '暂无描述';
            }
            if (!isset($newFormat[$item['cat_id']])) {
                $newFormat[$item['cat_id']] = [];
            }
            $newFormat[$item['cat_id']][] = $item;
        }

        return $newFormat;
    }

    public static function getLink($id){
        $link = self::field('id,cat_id,link,link_name,link_desc,link_img,link_keyword,target,is_pass')
            ->where([
                ['id', '=', $id],
                ['is_delete', '=', 0],
                ['status', '=', 0],
            ])->find();
        return $link;
    }

    public static function clickNum($id) {
        self::where('id', $id)
            ->inc('click_num')
            ->update(['update_at'=> date('Y-m-d H:i:s')]);
    }
}