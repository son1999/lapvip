<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class StoryWT extends Model {

    protected $table = 'story_wt';
    public $timestamps = false;

    public function storyImage(){
        return $this->hasMany(StoryImage::class, 'object_id', 'id');
    }
    public function storyCelebrities(){
        return $this->hasMany(StoryCelebrities::class, 'object_id', 'id');
    }


    public static function getImageGalleryProduct($hotel_id = 0,$type="hotel", $json = false){
        $images = StoryImage::where('type',$type)->where('object_id', $hotel_id)->orderByRaw('sort desc,created desc')->get();
        $data = [];
        foreach($images as $image){
            $tmp = $image->toArray();
            $tmp['img'] = $image->image;
            $tmp['image_sm'] = $image->getImageUrl('hotel_small');
            $tmp['image_md'] = $image->getImageUrl('hotel_preview');
            $tmp['image'] = $image->getImageUrl('hotel_large');
            $tmp['image_org'] = $image->getImageUrl();
            array_push($data, $tmp);
        }
        return $json ? json_encode($data) : $data;
    }
    public static function getImageGallerySlide($hotel_id = 0,$type="hotel", $json = false){
        $images = StoryImage::where('type',$type)->where('object_id', $hotel_id)->orderByRaw('sort desc,created desc')->get();
        $data = [];
        foreach($images as $image){
            $tmp = $image->toArray();
            $tmp['img'] = $image->image;
            $tmp['image_sm'] = $image->getImageUrl('hotel_small');
            $tmp['image_md'] = $image->getImageUrl('hotel_preview');
            $tmp['image'] = $image->getImageUrl('hotel_large');
            $tmp['image_org'] = $image->getImageUrl();
            array_push($data, $tmp);
        }
        return $json ? json_encode($data) : $data;
    }

    public static function getStoryCelebrities($hotel_id = 0,$type="hotel", $json = false){
        $images = StoryCelebrities::where('type',$type)->where('object_id', $hotel_id)->orderByRaw('created desc')->get();
        $data = [];
        foreach($images as $image){
            $tmp = $image->toArray();
            $tmp['img'] = $image->img_celebrities;
            $tmp['image_sm'] = $image->getImageUrlCele('hotel_small');
            $tmp['image_md'] = $image->getImageUrlCele('hotel_preview');
            $tmp['image'] = $image->getImageUrlCele('hotel_large');
            $tmp['image_org'] = $image->getImageUrlCele();
            array_push($data, $tmp);
        }
        return $json ? json_encode($data) : $data;
    }

    public function lang(){
        $lang = config('app.locales');
        return isset($lang[$this->lang]) ? $lang[$this->lang] : 'vi';
    }
    public function getImageUrl($size = 'original'){
        return \ImageURL::getImageUrl($this->image, 'story_wt', $size);
    }

}