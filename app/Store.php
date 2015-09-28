<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    // protected $fillable = ['name', 'phone', 'country', 'city', 'area', 'building'];

    // not working ?
    public function userRole($user_id){
        $user = $this->users->where('id',$user_id)->first();
        if($user == null) return null;
        return \App\Role::find($user->pivot->role_id)->name;
    }

    public function users(){
        return $this->belongsToMany('\App\User', 'role_user', 'store_id', 'user_id')->withTimestamps()->withPivot('role_id');
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
        return $this->belongsToMany('\App\Area')->withPivot('min','fee','feebelowmin');
    }

    public function coverageBuildings(){
        return $this->belongsToMany('\App\Building')->withPivot('min','fee','feebelowmin');;
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


}
