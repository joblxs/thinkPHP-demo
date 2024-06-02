<?php

namespace app\admin\common;

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;

class JwtUtil {

    private $signKey = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    private $keyId = "keyId";
    private $timeMinutes = 5;
    /**
     * 根据json web token设置的规则生成token
     * @return \think\response\Json
     */
    public function createToken($userId):string
    {
        $key = md5($this->signKey); //jwt的签发**，验证token的时候需要用到
        $time = time(); //签发时间
        $expire = $time + $this->timeMinutes*60; //过期时间
        $token = [
            "iss" => "http://lxshuai.top/",//签发组织
            "iat" => $time,    //签发时间
            "nbf" => $time,    // 生效时间
            "exp" => $expire,  //过期时间
            "admin" => [        // 包含的用户信息等数据
                "userId" => $userId,
            ]
        ];
        $jwt = JWT::encode($token, $key, 'HS256', $this->keyId);
        return $jwt;
    }

    /**
     * 验证token
     * @return \think\response\Json
     */
    public function verifyToken($token)
    {
        $key = new Key(md5($this->signKey), 'HS256'); //jwt的签发**，验证token的时候需要用到
        try{
            $jwtAuth = json_encode(JWT::decode($token, $key));
            $authInfo = json_decode($jwtAuth,true);
            if (!$authInfo['admin']){
                return ['code'=>401,'msg'=>"用户不存在"];
            }
            return ['code'=>200,'admin'=>$authInfo['admin'],'msg'=>"ok"];
        }catch (ExpiredException $e){
            return ['code'=>401,'msg'=>"token过期"];
        }catch (\Exception $e){
            return ['code'=>401,'msg'=>$e->getMessage()];
        }
    }
}