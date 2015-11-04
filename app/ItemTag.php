<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class itemTag extends Model
{
    
    static function tagList($store_type = '')
    {
        if($store_type == '') $tags = \App\itemTag::all();
        else $tags = \App\itemTag::where('store_type',$store_type)->get();

        $arr = array();
        foreach($tags as $tag)
                $arr[$tag->id] =  $tag->name;
        return ($arr);
    }



}
