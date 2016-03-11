<?php
/*************************************************************************
 *
 * Conglin Network CONFIDENTIAL
 * __________________
 *
 *  [2013] - [2015] Conglin Network Incorporated
 *  All Rights Reserved.
 *
 * NOTICE:  All information contained herein is, and remains
 * the property of Conglin Network Incorporated and its suppliers,
 * if any.  The intellectual and technical concepts contained
 * herein are proprietary to Conglin Network Incorporated
 * and its suppliers and may be covered by C.N. and Foreign Patents,
 * patents in process, and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from Conglin Network Incorporated.
 */

namespace App\Http;


class Menu
{
    // 现在只支持两级菜单
    public static function getMenu()
    {
        //region 进修班管理
        $menu['进修班管理']['icon'] = '<i class="fa fa-fw fa-user"></i>';
        $menu['进修班管理']['sub_menus']['进修班列表']['icon'] = '';
        $menu['进修班管理']['sub_menus']['进修班列表']['url'] = route('course');
        $menu['进修班管理']['sub_menus']['未发布进修班']['icon'] = '';
        $menu['进修班管理']['sub_menus']['未发布进修班']['url'] = route('course_unpub');
        $menu['进修班管理']['sub_menus']['添加进修班']['icon'] = '';
        $menu['进修班管理']['sub_menus']['添加进修班']['url'] = route('course.add');


        //endregion

        //region 申请管理
        $menu['申请管理']['icon'] = '<i class="fa fa-fw fa-user"></i>';

        $menu['申请管理']['sub_menus']['申请列表'] = [
        'icon' => '',
        'url' => route('application')
        ];
        /*$menu['申请管理']['sub_menus']['已取消申请'] = [
            'icon' => '',
            'url' => route('application_cancel')
        ];*/
        $menu['申请管理']['sub_menus']['未提交申请'] = [
            'icon' => '',
            'url' => route('application_unSubmit')
        ];
        $menu['申请管理']['sub_menus']['积分设置'] = [
            'icon' => '',
            'url' => route('score.tech_duty')
        ];
        /*$menu['申请管理']['sub_menus']['申请统计'] = [
            'icon' => '',
            'url' => route('application')
        ];*/
        //endregion

        //region 学员管理
        $menu['学员管理']['icon'] = '<i class="fa fa-fw fa-folder"></i>';

        $menu['学员管理']['sub_menus']['学员通讯录'] = [
            'icon' => '',
            'url' => route('contact')
        ];
        /*$menu['学员管理']['sub_menus']['学员证书'] = [
            'icon' => '',
            'url' => route('application')
        ];*/
        //endregion


        /*region 微信设置
        $menu['微信设置']['icon'] = '<i class="fa fa-fw fa-cog"></i>';

        $menu['微信设置']['sub_menus']['授权设置'] = [
             'icon' => '',
             'url' => route('settings.auth')
         ];
        $menu['微信设置']['sub_menus']['菜单设置'] = [
            'icon' => '',
            'url' => route('settings.menu')
        ];*/
        //endregion
        return $menu;
    }
}