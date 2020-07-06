<?php

namespace App\Models;

use function GuzzleHttp\Psr7\str;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //
    protected $table = 'menu';
    public $timestamps = false;
    protected static $splitKey = '_';
    const KEY = 'menu';
    public static $menuType = [
        0 => 'Admin Left Menu',
        1 => 'Admin Top Menu',
        2 => 'Admin Bottom Menu',
        3 => 'Public Header Menu',
        4 => 'Public Footer Menu',
        5 => 'Mobile Menu',
        9 => 'Khác'
    ];
    protected static $menu = [];
    const DEF_MENU_KEY = '99999999999';

    public function lang(){
        $lang = config('app.locales');
        return isset($lang[$this->lang]) ? $lang[$this->lang] : 'vi';
    }

    public function type(){
        return self::$menuType[$this->type];
    }

    public function getLink(){
//        dd($this);
        $def = '#';
        if(!empty($this->link)) {
            if(\Lib::isUrl($this->link)){
                return $this->link;
            }
            return \Route::has($this->link) ? route($this->link) : $def.$this->link;
        }
        return $def;
    }

    public static function getMenu($type = 0, $sys_menu_ok = false, $lang = ''){
        if(empty($lang)){
            $lang = \Lib::getDefaultLang();
        }
        $key = $type . '-' . $lang;
        if(empty(self::$menu[$key])) {
            $sql = [];
            if ($type >= 0) {
                $sql[] = ['type', '=', $type];
            }
            $sql[] = ['lang', '=', $lang];
            $sql[] = ['status', '>', 0];

            $data = self::where($sql)
                ->orderByRaw('type, pid, sort DESC, title')
                ->get()
                ->keyBy('id');
            $menu = [];
            if ($type < 0) {
                foreach ($data as $k => $v) {
                    if (!isset($menu[$v->type])) {
                        $menu[$v->type] = [
                            'title' => self::$menuType[$v->type],
                            'type' => $v->type,
                            'is_selling' => $v->is_selling,
                            'menu' => []
                        ];
                    }
                    $menu[$v->type]['menu'][$v->id] = $v;
                }
                foreach ($menu as $k => $v){
                    $menu[$k]['menu'] = self::fetchAll($v['menu'], $sys_menu_ok && ($type == 0));
                }
            } else {
//                dd($data);
                $menu = self::fetchAll($data, $sys_menu_ok && ($type == 0));
            }
            self::$menu[$key] = $menu;
        }
        return self::$menu[$key];
    }
    public static function getMenuWithFilter($type = 0, $sys_menu_ok = false, $lang = ''){
        if(empty($lang)){
            $lang = \Lib::getDefaultLang();
        }
        $key = $type . '-' . $lang;
        if(empty(self::$menu[$key])) {
            $sql = [];
            if ($type >= 0) {
                $sql[] = ['type', '=', $type];
            }
            $sql[] = ['lang', '=', $lang];
            $sql[] = ['status', '>', 0];

            $data = self::where($sql)
                ->orderByRaw('type, pid, sort DESC, title')
                ->get()
                ->keyBy('id');
            $menu = [];
            if ($type < 0) {
                foreach ($data as $k => $v) {
                    if (!isset($menu[$v->type])) {
                        $menu[$v->type] = [
                            'title' => self::$menuType[$v->type],
                            'type' => $v->type,
                            'is_selling' => $v->is_selling,
                            'menu' => []
                        ];
                    }
                    $menu[$v->type]['menu'][$v->id] = $v;
                }
                foreach ($menu as $k => $v){
                    $menu[$k]['menu'] = self::fetchAll($v['menu'], $sys_menu_ok && ($type == 0));
                }
            } else {
//                dd($data);
                $menu = self::fetchAll($data, $sys_menu_ok && ($type == 0));
            }
            self::$menu[$key] = $menu;
        }
        return self::$menu[$key];
    }

    public static function fetchAll($data, $sys_menu = false){
        $menu = [];
        foreach ($data as $k => $v) {
            if ($v->pid == 0) {
                $menu[self::$splitKey.$v->id] = self::fetchMenu($v);
                unset($data[$k]);
            } elseif (isset($menu[self::$splitKey.$v->pid])) {
                $menu[self::$splitKey.$v->pid]['sub'][self::$splitKey.$v->id] = self::fetchMenu($v);
                unset($data[$k]);
            }
        }
        foreach ($data as $v) {
            foreach ($menu as $pid => $item){
                foreach ($item['sub'] as $id => $sub){
                    if(self::$splitKey.$v->pid == $id){
                        $menu[$pid]['sub'][$id]['sub'][self::$splitKey.$v->id] = self::fetchMenu($v);
                    }
                }
            }
        }
        if($sys_menu){
            $menu[self::$splitKey.self::DEF_MENU_KEY] = self::defAdminMenu();
        }
        return $menu;
    }

    public static function fetchMenu($menu){
        $selling = [];
        if (!empty($menu->id_selling)){
            foreach (explode(',', $menu->id_selling) as $sell){
                $selling [] = $sell;
            }
        }
        $pro  = Product::where('status', '>', 1)->whereIn('id', $selling)->limit(3)->get()->toArray();
        $out = [
            'id' => $menu->id,
            'title' => $menu->title,
            'link' => $menu->getLink(),
            'perm' => !empty($menu->perm) ? $menu->perm : '',
            'no_follow' => !empty($menu->no_follow) ? $menu->no_follow : 1,
            'newtab' => !empty($menu->newtab) ? $menu->newtab : 0,
            'icon' => !empty($menu->icon) ? $menu->icon : '',
            'sub' => [],
            'img_icon' => $menu->img_icon,
            'image' => $menu->image,
            'banner_link' => $menu->banner_link,
            'cat_id' => $menu->cat_id,
            'is_selling' => $pro,
            'cat_id_footer' => $menu->cat_id_footer,
        ];
        return $out;
    }

    protected static function defAdminMenu(){
        $menu = self::fetchMenu(self::createDefaultMenu([
            'id' => self::DEF_MENU_KEY,
            'title' => 'Cấu hình hệ thống',
            'perm' => ''
        ]));
        array_push($menu['sub'], self::fetchMenu(self::createDefaultMenu([
            'id' => self::DEF_MENU_KEY+1,
            'title' => 'Menu',
            'icon' => 'icon-menu',
            'perm' => 'menu-view',
            'link' => 'admin.menu'
        ])));

        array_push($menu['sub'], self::fetchMenu(self::createDefaultMenu([
            'id' => self::DEF_MENU_KEY+2,
            'title' => 'Người dùng',
            'icon' => 'icon-user',
            'perm' => 'user-view',
            'link' => 'admin.user',
        ])));

        array_push($menu['sub'], self::fetchMenu(self::createDefaultMenu([
            'id' => self::DEF_MENU_KEY+3,
            'title' => 'Phân quyền',
            'icon' => 'icon-flag',
            'perm' => 'role-view',
            'link' => 'admin.role',
        ])));

        array_push($menu['sub'], self::fetchMenu(self::createDefaultMenu([
            'id' => self::DEF_MENU_KEY+4,
            'title' => 'Cấu hình',
            'icon' => 'icon-settings',
            'perm' => 'config-change',
            'link' => 'admin.config',
        ])));
        return $menu;
    }

    protected static function createDefaultMenu($menu){
        $a = new Menu();
        foreach ($menu as $k => $v){
            $a->$k = $v;
        }
        return $a;
    }
    public function getImageUrl($size = 'original'){
        return \ImageURL::getImageUrl($this->img_icon, 'menu', $size);
    }
    public function getImageBanner($size = 'original'){
        return \ImageURL::getImageUrl($this->image, 'menu', $size);
    }

}
