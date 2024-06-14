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
        $links = self::alias('nl')
            ->field('nl.id,nl.cat_id,nl.link_name,nl.link_desc,nl.link_img,nl.link_keyword,nl.is_pass')
            ->join('nav_categorie nc', 'nl.cat_id = nc.id')
            ->where([
                ['nl.is_delete', '=', 0],
                ['nl.status', '=', 0],
                ['nc.is_delete', '=', 0],
                ['nc.status', '=', 0],
            ])
            ->order(['nl.sort' => 'desc', 'nl.id' => 'asc'])->select()->toArray();
        $newFormat = [];
        foreach ($links as $item) {
            if (empty($item['link_img'])) {
                $randomImage = @\app\api\model\Lsky::randomImages('', 'thumbnail_url');
                $item['link_img'] = $randomImage;
            } else {
                $item['link_img'] = $item['link_img'];
            }
            if (empty($item['link_desc'])) {
                $item['link_desc'] = '暂无描述';
            }
            if (!isset($newFormat[$item['cat_id']])) {
                $newFormat[$item['cat_id']] = [];
            }
            $newFormat[$item['cat_id']][] = $item;
        }

        return $newFormat;
    }

    public static function getLink($id){
        $link = self::field('id,cat_id,link,link_name,link_desc,link_img,link_keyword,target,is_pass,password')
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