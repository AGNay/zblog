<?php
/**
 * Created by PhpStorm.
 * User: Kilingzhang  <slight@kilingzhang.com>
 * Date: 2017/8/19
 * Time: 19:22
 */

namespace NeteaseCloudMusicSDK;

use Utils\Request;

class Banner
{
    /**
     * @route GET /banner
     * @return string json
     */
    public function banner()
    {
        $Request = new Request();
        $data = array(

            'csrf_token' => '',
        );
        $response = $Request->createWebAPIRequest(
            "http://music.163.com",
            "/weapi/v2/banner/get",
            'POST',
            $data
        );
        return json_decode($response, true);
    }
}