<?php

namespace app\admin\middleware;

use think\Request;
use app\admin\common\JwtUtil;
use think\Response;

class CheckJwt
{
    public function handle(Request $request, \Closure $next)
    {
        // $adminConfig = config('admin');
        // var_dump($adminConfig['no_login_node']);
        // var_dump(123);

        // var_dump(request()->controller() . '/' . request()->action());
        // die;
        // $authorization = $request->header('Authorization');
        // if (!$authorization) {
        //     return Response::create(['error' => '缺少Token'], 'json', 401);
        // }

        // $jwtUtil = new JwtUtil();
        // $token = str_replace('Bearer ', '', $authorization);
        // try {
        //     $jwtUtil->verifyToken($token); 
        // } catch (\Exception $e) {
        //     return Response::create(['error' => '无效的Token'], 'json', 401);
        // }

        return $next($request);
    }
}