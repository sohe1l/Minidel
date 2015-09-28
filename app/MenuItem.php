<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menuItem extends Model
{
    protected $fillable = ['menu_section_id','title','info','price'];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model)
        {
            $model->order = $model->lastOrder() + 1;
        });
    }



    public function store(){
        return $this->belongsTo('\App\Store');
    }
    public function menuSection(){
        return $this->belongsTo('\App\menuSection');
    }
    public function options(){
        return $this->belongsToMany('\App\menuOption');
    }

    public function optionsSelectedList()
    {
        $arr = array();
        foreach($this->options as $option)
            $arr[] =  $option->id;
        return ($arr);
    }

    public function optionsListSelect2()
    {
        $arr = array();
        foreach($this->options as $option)
                $arr[] = array('id'=> $option->id,'text'=>$option->name);
        return ($arr);
    }

    public function lastOrder()
    {
        

        $section = $this->menuSection;
        if( $section == null) return -1;

        $items = $section->items;
        if( count($items) == 0) return -1;

        return $items->sortBy('order')->last()->order;

        // return $this->menuSection->items->sortBy('order')->last()->order;
    }

/*
    //set the order to the last item in that section
    public function setOrderAttribute($order='')
    {
        dd($order);
        //find the last item in the section

        //
    }

    // arrenge by order
    public function scopeInorder($query)
    {
        dd($query);
        //find the last item in the section

        //
    }

    public function addPhoto(Photo $photo)
    {
        # code...
    }
*/

}
