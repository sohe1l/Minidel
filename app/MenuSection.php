<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menuSection extends Model
{
    protected $fillable = ['title'];

    public function store(){
        return $this->belongsTo('\App\Store');
    }

    public function items(){
        return $this->hasMany('\App\menuItem');
    }

    public function subsections()
    {
        return $this->hasMany('\App\menuSection');
    }


    public function menuSection()
    {
        return $this->belongsTo('\App\menuSection');
    }

    public function scopeAvailable($query)
    {
        return $query->where('available', 1);
    }


    public function scopeIsParent($query)
    {
        return $query->where('menu_section_id', null);
    }


    static function listForSelect($parent_only = false){
        $out = [];
        $sections = \App\menuSection::select('id', 'title');
        if($parent_only) $sections = $sections->isParent();
        $sections= $sections->get();

        foreach($sections as $s){
            $out[$s->id] = $s->title;
        }
        return $out;
    }

    static function selectFromCollection($sections, $parent_only = false){
        $out = [];
        if($parent_only) $sections = $sections->where('menu_section_id',null);

        foreach($sections as $s){
            $out[$s->id] = $s->title;
        }
        return $out;
    }


}
