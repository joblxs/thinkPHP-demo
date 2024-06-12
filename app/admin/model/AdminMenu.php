<?php
namespace app\admin\model;

use think\Model;

class AdminMenu extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'admin_menu';
    // 设置时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';
    protected $deleteTime = 'delete_at';

    // 菜单管理列表
    public static function getMenuList(){
        $menuList = self::where('is_delete', 0)
            ->order(['sort' => 'desc', 'id' => 'asc'])
            ->select();
        return $menuList;
    }

    // 获取菜单树形列表
    public static function getMenuTree($pid = 0){
        $menuList = self::field('id,pid,title,icon,href,target')
            ->where('is_delete', 0)
            ->order(['sort' => 'desc', 'id' => 'asc'])
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


    // 添加/修改页面上级菜单选择
    public static function getPidMenuList() {
        $list = self::field('id,pid,title')
            ->where([
                ['is_delete', '=', 0],
                ['pid', '=', 0],
            ])->order(['sort' => 'desc', 'id' => 'asc'])
            ->select()
            ->toArray();
        foreach ($list as $key => $vo) {
            $repeatString = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            $markString   = str_repeat("{$repeatString}├{$repeatString}", 1);
            $vo['title']  = $markString . $vo['title'];
            $list[$key] = $vo;
        }
        $pidMenuList = array_merge([[
            'id'    => 0,
            'pid'   => 0,
            'title' => '顶级菜单',
        ]], $list);
        return $pidMenuList;
    }
}