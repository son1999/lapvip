<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigSite extends Model
{
    //
    protected $table = '__configs';
    public $timestamps = false;
    protected $primaryKey = 'key';

    public static function setConfig($key = '', $value = '') {
        $record = ConfigSite::where('key', $key)->first();
        if(empty($record)){
            $record = new ConfigSite();
            $record->key = $key;
        }
        $record->value = $value;
        return $record->save();
    }

    public static function getConfig($key = '', $def = '') {
        $record = ConfigSite::where('key', $key)->first();
        if(!empty($record)){
            $data = $record->value;
            $value = !empty($data) ? json_decode($data, true) : null;
            if(!empty($value['image'])){
                $value['image_medium_seo'] = \ImageURL::getImageUrl($value['image'], 'config', 'medium_seo');
                $value['image_seo'] = \ImageURL::getImageUrl($value['image'], 'config', 'seo');
            }
            return json_encode($value);
        }
        return $def;
    }

    public static function getSeo(){
        $data = self::getConfig('config');
        $data = !empty($data) ? @json_decode($data,true) : [];
        $return = [];
        if(!empty($data)){
            $data['image_seo'] = (isset($data['image_seo']) ? $data['image_seo'] : '');
            $return['meta_basic'] = '
<meta name="title" content="' . $data['site_name'] . '"/>
<meta name="description" content="' . $data['description'] . '"/>
<meta name="keywords" content="' . $data['keywords'] . '"/>';
            $return['facebook_meta'] = '
<meta name="description" content="'.$data['description'].'"/>
<meta name="keywords" content="'.$data['keywords'].'"/>
<meta property="og:locale" content="vi_VN" />
<meta property="og:title" content="'.$data['site_name'].'" />
<meta property="og:description" content="'.$data['description'].'" />
<meta property="og:url" content="'.url()->current().'" />
<meta property="og:site_name" content="'.$data['site_name'].'" />
<meta property="og:image" content="'.$data['image_seo'].'" />
<meta property="og:image:width" content="800" />
<meta property="og:image:height" content="800" />
            ';
            $return['twitter_meta'] = '
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:description" content="'.$data['description'].'" />
<meta name="twitter:title" content="'.$data['site_name'].'" />
<meta name="twitter:image" content="'.$data['image_seo'].'" />
<meta name="twitter:site" content=”'.url()->current().'” />
            ';
            $return['g_meta'] = '
<meta itemprop="name" content="'.$data['site_name'].'" />
<meta itemprop="description" content="'.$data['description'].'" />
<meta itemprop="image" content="'.$data['image_seo'].'">
            ';
        }
        return $return;
    }

}
