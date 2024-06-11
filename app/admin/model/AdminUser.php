<?php
namespace app\admin\model;

use think\Model;

class AdminUser extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'admin_user';
    // 设置时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';
    protected $deleteTime = 'delete_at';

    // 根据用户名获取管理员信息
    public static function getAdmin($username){
        $admin = self::where('username', $username)->find();
        return $admin;
    }

    public static function loginNum($username) {
        self::where('username', $username)
            ->inc('login_num')
            ->update([
                'update_at'=> date('Y-m-d H:i:s')
            ]);
    }
}