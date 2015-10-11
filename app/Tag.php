<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{


    public function stores(){
        return $this->belongsToMany('\App\Store');
    }


    static function tagList($type = '')
    {
        if($type == '') $tags = \App\Tag::all();
        else $tags = \App\Tag::where('type',$type)->get();

        $arr = array();
        foreach($tags as $tag)
                $arr[$tag->id] =  $tag->name;
        return ($arr);
    }

    static function tagList2($type = '')
    {
        if($type == '') $tags = \App\Tag::all();
        else $tags = \App\Tag::where('type',$type)->get();

        $arr = array();
        foreach($tags as $tag)
                $arr[] = ['id'=>$tag->id, 'text'=>$tag->name];
        return ($arr);
    }


}
