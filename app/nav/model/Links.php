<?php
namespace app\nav\model;

use think\Model;

class Links extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'nav_links';
    // 设置时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    public static function getAllLinks()
    {
        $links = self::select()->toArray();
        $newFormat = [];
        foreach ($links as $item) {
            if (!isset($newFormat[$item['cat_id']])) {
                $newFormat[$item['cat_id']] = [];
            }
            $newFormat[$item['cat_id']][] = $item;
        }

        return $newFormat;
    }
}