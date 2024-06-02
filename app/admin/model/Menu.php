<?php
namespace app\admin\model;

use think\Model;

class Menu extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'admin_menu';
    // 设置时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';
    protected $deleteTime = 'delete_at';

    // 获取菜单列表
    public static function getMenuList($pid = 0){
        $menuList = self::field('id,pid,title,icon,href,target')
            ->where('status', 1)
            ->order('sort', 'desc')
            ->select();
        $menuList = self::buildMenuChild($pid, $menuList);
        return $menuList;
    }

    //递归获取子菜单
    public static function buildMenuChild($pid, $menuList){
        $treeList = [];
        foreach ($menuList as $v) {
            if ($pid == $v['pid']) {
                $node = $v;
                $child = self::buildMenuChild($v['id'], $menuList);
                if (!empty($child)) {
                    $node['child'] = $child;
                }
                // todo 后续此处加上用户的权限判断
                $treeList[] = $node;
            }
        }
        return $treeList;
    }
}