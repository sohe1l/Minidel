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

    public function tags(){
        return $this->belongsToMany('\App\itemTag');
    }

    public function tagsSelectedList()
    {
        $arr = array();
        foreach($this->tags as $tag)
            $arr[] =  $tag->id;
        return ($arr);
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

    static function priceArray($idArr){
        $out = [];
        $products = \App\menuItem::whereIn('id', $idArr)->select('id', 'price')->get();
        foreach($products as $p){
            $out[$p->id] = $p->price;
        }
        return $out;
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







    public function logoFromForm(UploadedFile $file){
        $this->saveAs($file->getClientOriginalName(),'logo');
        $this->logoSaveFile($file->getRealPath());
    }

    public function logoFromUrl($url){
        $client = new \GuzzleHttp\Client();
        $body = (string) $client->request('GET', $url)->getBody();

        $this->saveAs($this->slug . str_random(2) . '.jpg' ,'logo');
        $this->logoSaveFile($body);
    }


    private function logoSaveFile($imageRef){
        $image = Image::make($imageRef);

        // prevent possible upsizing
        $image->resize(150, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $canvas = Image::canvas($image->width(), $image->height(), 'ffffff');
        $canvas->insert($image);
        $canvas->save($this->logo);
    }





    public function PhotoFromForm(UploadedFile $file){
        $this->saveAs($file->getClientOriginalName());
        $this->coverSaveFile($file->getRealPath());
    }
    
    public function coverFromUrl($url){
        $client = new \GuzzleHttp\Client();
        $body = (string) $client->request('GET', $url)->getBody();

        $this->saveAs($this->slug . str_random(2) . '.jpg' ,'cover');
        $this->coverSaveFile($body);
    }


    public function coverSaveFile($imageRef){
        $image = Image::make($imageRef);
        $image->fit(1200,300)->save($this->cover);

        $image = Image::make($imageRef);
        $image->fit(400,100)->save($this->cover_thumb);
    }


    protected function saveAs($name){
            $file_name = sprintf("%s-%s", time(), substr($name,0,30));
            $this->cover = sprintf("%s/%s", $this->baseDir, $cover_name);
            $this->cover_thumb = sprintf("%s/tn-%s", $this->baseDir, $cover_name);
    }

    public function deleteLogo(){
        File::delete($this->logo);
    }


}
