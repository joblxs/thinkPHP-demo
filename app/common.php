<?php
// 应用公共文件

if (!function_exists('password')) {

    /**
     * 密码加密算法
     * @param $value 需要加密的值
     * @param $type  加密类型，默认为md5 （md5, hash）
     * @return mixed
     */
    function password($value)
    {
        $value = sha1('admin_') . md5($value) . md5('_encrypt') . sha1($value);
        return sha1($value);
    }
}