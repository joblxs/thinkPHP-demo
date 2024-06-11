<?php
namespace app\admin\model;

use think\Model;

class NavCategorie extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'nav_categorie';
    // 设置时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';
    protected $deleteTime = 'delete_at';

    // 分类管理列表
    public static function getCateList(){
        $cateList = self::where('is_delete', 0)
            ->order(['sort' => 'desc', 'id' => 'asc'])
            ->select();
        return $cateList;
    }

    // 获取分类树形列表
    public static function getCateTree($pid = 0){
        $cateList = self::field('id,pid,title,icon,href,target')
            ->where('is_delete', 0)
            ->order(['sort' => 'desc', 'id' => 'asc'])
            ->select();
        $cateList = self::buildCateChild($pid, $cateList);
        return $cateList;
    }

    //递归获取子分类
    public static function buildCateChild($pid, $cateList){
        $treeList = [];
        foreach ($cateList as $v) {
            if ($pid == $v['pid']) {
                $node = $v;
                $child = self::buildCateChild($v['id'], $cateList);
                if (!empty($child)) {
                    $node['child'] = $child;
                }
                // todo 后续此处加上用户的权限判断
                $treeList[] = $node;
            }
        }
        return $treeList;
    }


    // 添加/修改页面上级分类选择
    public static function getPidCateList() {
        $list = self::field('id,pid,title')
        ->where([
            ['is_delete', '=', 0],
        ])
        ->select()
        ->toArray();
        $pidCateList = self::buildPidCate(0, $list);
        return $pidCateList;
    }
    protected static function buildPidCate($pid, $list, $level = 0)
    {
        $newList = [];
        foreach ($list as $vo) {
            if ($vo['pid'] == $pid) {
                $level++;
                foreach ($newList as $v) {
                    if ($vo['pid'] == $v['pid'] && isset($v['level'])) {
                        $level = $v['level'];
                        break;
                    }
                }
                $vo['level'] = $level;
                if ($level > 1) {
                    $repeatString = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    $markString   = str_repeat("{$repeatString}├{$repeatString}", $level - 1);
                    $vo['title']  = $markString . $vo['title'];
                }
                $newList[] = $vo;
                $childList = self::buildPidCate($vo['id'], $list, $level);
                !empty($childList) && $newList = array_merge($newList, $childList);
            }

        }
        return $newList;
    }

}