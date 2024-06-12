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

    // 添加/修改页面上级分类选择
    public static function getPidCateList() {
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
        $pidCateList = array_merge([[
            'id'    => 0,
            'pid'   => 0,
            'title' => '顶级分类',
        ]], $list);
        return $pidCateList;
    }

    // 添加/修改链接选择
    public static function getCatesList() {
        $list = self::field('id,pid,title')
            ->where([
                ['is_delete', '=', 0],
            ])
            ->order(['sort' => 'desc', 'id' => 'asc'])
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