<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];



    public static function boot()
    {
        parent::boot();
        static::creating(function($user){
            $user->confirmation_code = str_random(30);

            //unique slug
            $user->username = str_slug($user->name);

            $index = 1;

            while(User::where('username', '=', $user->username)->exists()){
                $user->username = str_slug($user->name) . '-' . $index++;
            }



        });
    }

    function isAdmin(){
        if($this->id == 1) return true;
        return false;
    }

    public function setPasswordAttribute($password){
        $this->attributes['password'] = bcrypt($password);
    }


    public function roles()
    {
        return $this->belongsToMany('\App\Role')->withTimestamps()->withPivot('store_id');
    }

    // not workinggggggggggg
    public function storeRole($store_id){
        $store = $this->stores->where('id',$store_id)->first();
        if($store == null) return null;
        return \App\Role::find($store->pivot->role_id)->first()->name;
    }


    public function stores(){
        return $this->belongsToMany('\App\Store', 'role_user', 'user_id', 'store_id')->withTimestamps()->withPivot('role_id');
    }

    public function addresses(){
        return $this->hasMany('\App\UserAddress');
    }

    public function orders(){
        return $this->hasMany('\App\Order');
    }

    public function photos(){
        return $this->hasMany('\App\Photo');
    }


    public function hasAddresses(){
        if($this->addresses->count() != 0 ) return true;
        return false;
    }


    public function listAddresses()
    {
        $arr = array();
        foreach($this->addresses as $address)
                $arr[$address->id] =  $address->name;
        if(count($arr) == 0) return null;
        return ($arr);
    }


    public function listAddressesByStore($storeid)
    {
        $store = \App\Store::find($storeid);
        if(!$store) return null;

        $coverage = $store->coverageBuildings->keyBy('id')->keys()->toArray(); // idis of buildings

        
        
        $arr_valid = array();
        foreach($this->addresses as $address){
            if(in_array($address->building_id, $coverage)){
                // $arr_valid[$address->id] =  $address->name;
                $arr_valid[] =  array("text"=>$address->name ,"value"=>$address->id);
            }
        }
                        
        if(count($arr_valid) == 0) return null;
        return ($arr_valid);
    }


    /**
     * Does the user have a particular role?
     *
     * @param $name
     * @return bool
     */
    public function hasRole($name)
    {
        foreach ($this->roles as $role)
        {
            if ($role->name == $name) return true;
        }
        return false;
    }

}
