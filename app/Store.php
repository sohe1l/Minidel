<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    // protected $fillable = ['name', 'phone', 'country', 'city', 'area', 'building'];

    protected $appends = ['is_open','is_deliver_building','is_deliver_area'];

    protected $hidden = ['email','comission'];
    

    public function scopeListed($query)
    {
        return $query->where('status_listing', 'published');
    }

    
    public function scopeAcceptOrders($query)
    {
        return $query->where('accept_orders', 1);
    }

    //used in MaintainStores Command
    public function scopeIsNotOpen($query)
    {
        return $query->where('status_working', '!=', 'open');
    }

    public function scopeStatusWorkingIsExpired($query)
    {
        return $query->where('status_working_expire', '<', \Carbon\Carbon::now());
    }



    // not working ?
    public function userRole($user_id){
        if( in_array($user_id, \Config::get('vars.superAdmins') ) ) {
            return 'store_owner';
        }

        $user = $this->users->where('id',$user_id)->first();
        if($user == null) return null;
        return \App\Role::find($user->pivot->role_id)->name;
    }

    public function users(){
        return $this->belongsToMany('\App\User', 'role_user', 'store_id', 'user_id')->withTimestamps()->withPivot('role_id');
    }

    public function chain(){
        return $this->belongsTo('\App\Chain');
    }

    public function city(){
        return $this->belongsTo('\App\City');
    }

    public function area(){
        return $this->belongsTo('\App\Area');
    }

    public function building(){
        return $this->belongsTo('\App\Building');
    }
    
    public function sections(){
        return $this->hasMany('\App\menuSection');
    }

    public function items(){
        return $this->hasManyThrough('\App\menuItem','\App\menuSection');
    }
    
    public function options(){
        return $this->hasMany('\App\menuOption');
    }

    public function optionsList()
    {
        $arr = array();
        foreach($this->options as $option)
                $arr[$option->id] =  $option->name;
        return ($arr);
    }

    public function optionsListSelect2()
    {
        $arr = array();
        foreach($this->options as $option)
            $arr[] = array('id'=> $option->id,'text'=>$option->name);
        return ($arr);
    }

    public function coverageAreas(){
        return $this->belongsToMany('\App\Area')->withPivot('min','fee','feebelowmin','discount');
    }

    public function coverageBuildings(){
        return $this->belongsToMany('\App\Building')->withPivot('min','fee','feebelowmin','discount');;
    }

    public function timings(){
        return $this->hasMany('\App\Timing');
    }

    public function timingsSpecific($mode,$day){
        $workmode = \App\Workmode::whereName($mode)->first();
        if(! $workmode) return null;
        return $this->timings()->where('day',strtolower($day))->where('workmode_id',$workmode->id)->orderBy('start')->get();
    }
    
    public function isOpenAt($workmode_name,$day,$time)
    {
        $workmode = \App\Workmode::whereName($workmode_name)->first();
        if($workmode == null) return "false";

        $timing = $this->timings()->where('workmode_id',$workmode->id)->where('day',$day)
                       ->where('start','<=' ,$time)->where('end','>=' ,$time)->get();
        if($timing->count() != 0) return "true";
        return "false";
    }

    public function isOpenAtTimestamp($workmode_name,$timestamp){
        $day = strtolower(date("D",$timestamp));
        $time = date("H:i:s",$timestamp);

        return $this->isOpenAt($workmode_name, $day, $time);
    }

    public function isOpenNow($workmode_name){
        $day = strtolower(date("D"));
        $time = date("H:i:s");

        return $this->isOpenAt($workmode_name, $day, $time);
    }

    public function itemsWithOptions(){
        return $this->items()->with('options.options');
    }

    public function orders(){
        return $this->hasMany('\App\Order');
    }

    public function getIsOpenAttribute(){
        return $this->isOpenNow('Normal Openning');
    }
    public function getIsDeliverBuildingAttribute(){
        return $this->isOpenNow('Building Delivery');
    }
    public function getIsDeliverAreaAttribute(){
        return $this->isOpenNow('Area Delivery');
    }


    public function updateLastCheck(){
        $this->last_check = date('Y-m-d H:i:s');
        $this->save();
    }



    public function tags(){
        return $this->belongsToMany('\App\Tag');
    }

    public function ratings(){
        return $this->hasMany('\App\Rating');
    }


    public function lastSectionOrder($parent = null)
    {
        if($this->sections()->where('menu_section_id',$parent)->count() == 0) return -1;
        $section = $this->sections()->where('menu_section_id',$parent)->orderBy('order','desc')->first();
        return $section->order;
    }


    public function lastMenuPhotoOrder()
    {
        if($this->photos()->where('type','menu')->count() == 0) return -1;
        $photo = $this->photos()->where('type','menu')->orderBy('order','desc')->first();
        return $photo->order;
    }

    public function transactions(){
        return $this->hasMany('\App\Transaction');
    }

    public function payments(){
        return $this->belongsToMany('\App\paymentType')->where('slug','!=','card');
    }


    public function photos()
    {
        return $this->morphMany('App\Photo', 'imageable');
    }



    public function promos(){
        return $this->hasMany('\App\Promo');
    }

}
