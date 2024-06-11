<?php
namespace app\admin\model;

use think\Model;

class NavLink extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'nav_links';
    // 设置时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';
    protected $deleteTime = 'delete_at';

    public static function getLinkList() {
        $list = self::alias('nl')
            ->field('nl.*, nc.title')
            ->where('nl.is_delete', 0)
            ->join('nav_categorie nc', 'nl.cat_id = nc.id')
            ->order('nl.sort', 'desc')
            ->select();
        return $list;
    }
}
