<?php

namespace tourze\Controller;

use tourze\Base\Base;
use tourze\Http\Exception\HttpException;

/**
 * 最基础的Web控制器
 *
 * @package tourze\Controller
 */
abstract class WebController extends Controller
{

    /**
     * 跳转的助手方法
     *
     * @param  string $uri  要跳转的URI
     * @param  int    $code HTTP状态码
     * @throws HttpException
     */
    public function redirect($uri = '', $code = 302)
    {
        Base::getLog()->debug(__METHOD__ . ' redirect from controller', [
            'uri'  => $uri,
            'code' => $code,
        ]);
        Base::getHttp()->redirect((string) $uri, $code);
    }
}
