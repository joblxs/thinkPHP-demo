<?php
namespace app\nav\model;

use think\Model;

class Categories extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'nav_categories';
    // 设置时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    // 获取所有分类并构建树状结构
    public static function getAllCategoriesTree()
    {
        $categories = self::field(['id', 'pid', 'name', 'icon'])
            ->where('is_deleted', 0)
            ->where('sort', 0)
            ->select()->toArray();
        return self::buildTree($categories);
    }

    // 递归构建树状结构
    public static function buildTree($data, $parentId = 0, $level = 0)
    {
        $tree = [];
        foreach ($data as $item) {
            if ($item['pid'] == $parentId) {
                $item['level'] = $level; // 添加层级信息
                $item['children'] = self::buildTree($data, $item['id'], $level + 1);
                $tree[] = $item;
            }
        }
        return $tree;
    }

    // 定义子分类的关联
    public function children()
    {
        return $this->hasMany(self::class, 'pid', 'id');
    }
}