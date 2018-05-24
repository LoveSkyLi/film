<?php

namespace app\admin\model;

use think\Model;

class Setting extends Model
{
    // const TYPE_SLIDER = 1; // 轮播图
    // const TYPE_NAVBAR = 2; // 导航
    // const TYPE_ADONE = 3;  //.头部广告
    // const TYPE_ADTWO = 4;  // 本月主打
    const TYPE_ADTHREE =5; 

    public static function getModule()
    {
        $map = [
            'rate' => '收益率'
//             'goods' => '商品',
// //            'article' => '文章',
//             'type' => '商品分类',
//             'page' => '页面'
        ];

        return $map;
    }

//     public static function getPage()
//     {
//         $map = [
//             [
//                 'id' => '/pages/company/index/index',
//                 'title' => '关于我们',
//             ],
//             [
//                 'id' => '/pages/goods/list/index',
//                 'title' => '商品列表',
//             ],
// //            [
// //                'id' => '/pages/form/index',
// //                'title' => '预约婚纱',
// //            ],
// //            [
// //                'id' => '/pages/company/contact/index',
// //                'title' => '联系我们',
// //            ],
//         ];

//         return $map;
//     }
}
