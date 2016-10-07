<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $dpBase = 'img/user/';
    protected $dpBaseTiny = 'img/user-tiny/';

    // not used anymore becasue it does not open status correctly !!!!
    var $store_columns = ['stores.name','stores.slug','stores.country','stores.area_id','stores.city_id',
                            'stores.building_id','stores.status_working','stores.info',
                            'stores.logo','stores.cover']; // used in dashbaord.orderStores / browse.search


    public function index()
    {
        $user = \Auth::user();

        if ($user->addresses->count() == 0){
            flash("Please add your address to be able to make an order!");
            return redirect('/dashboard/address/create/');  
        } 
        
        $recent = $user->orders()->groupby('store_id')->take(5)->get(); // ->where('status','delivered')

        $user_addresses = $user->addresses()->with('city','area','building')->get();


        // $all_promotions = new \Illuminate\Database\Eloquent\Collection;
        foreach($user_addresses as $ua){

            $promos = $ua->area->coverageStores()->whereHas('promos', function($q){
                $q->whereDate('start_date', '<=' , Carbon::now())->whereDate('end_date', '>=' , Carbon::now());
            })->with(['promos' => function($q){
                $q->whereDate('start_date', '<=' , Carbon::now())->whereDate('end_date', '>=' , Carbon::now()); 
            }])->get();


            if($promos)
                $ua->promos = $promos;
                //$all_promotions = $all_promotions->merge($promos);
            
            //set has mini
            $ua->has_mini = false;
            $building = $ua->building;
            if($building){
                $building_stores = $building->coverageStores;
                if($building_stores->count() > 0) $ua->has_mini = true;
            }

        }





        $all_orders = new \Illuminate\Database\Eloquent\Collection;
        foreach($user_addresses as $ua){

            $recent_orders = $ua->orders()->with('store')->orderBy('created_at', 'desc')->take(6)->get();
            //dd($recent_orders);
            if($recent_orders)
                $all_orders = $all_orders->merge($recent_orders);
        }
        foreach($all_orders as $ao){ // prase the cart for vuejs
            $ao->cart = json_decode($ao->cart);
        }

        //dd($all_orders);

        return view('dashboard.index', compact('user','recent','user_addresses','all_orders'));
    }


    public function running()
    {
        $user = \Auth::user();


        return view('dashboard.running', compact('user'));
    }




    public function orderRegular(Request $request){
        $user = \Auth::user();

        $oo = \App\Order::find($request->order);
        if(!$oo) return jsonOut(1,'Order does not exists.');

        $store = \App\Store::find($oo->store_id);
        if(!$store) return jsonOut(1,'Store does not exists.');

        
        //check if discount is same
        if($oo->discount != 0){
            $active_promo = $store->promos()->active()->first();
            if(!$active_promo || $oo->discount != $active_promo->value){
                return jsonOut(1,"The store promotion has ended or changed. Please order again from the store page.");
            }
        }
        
        $response = saveOrder($store, $user, json_decode($oo->cart), $oo->payment_type_id, $oo->price, $oo->fee, $oo->instructions, $oo->type, $oo->user_address_id);

        if(is_array($response)) return response()->json($response);
        else return jsonOut(1,$response);  

    }


    public function order()
    {
        $user = \Auth::user();
        if ($user->addresses->count() == 0){
            flash("Please add your address to be able to make an order!");
            return redirect('/dashboard/address/create/');  
        } 

/*
        $merged = new \Illuminate\Database\Eloquent\Collection;

        foreach( $user->addresses  as $address){
            $building = \App\Building::find($address->building_id);
            if($building)
                $merged = $merged->merge($building->coverageStores);
        }
*/

        return view('dashboard.order', compact('user'));
    }


    public function orderStores(Request $request)
    {
        //$store = \App\Store::find(1);
        //dd($store->append('is_open'));

        $error = 0;
        $user = \Auth::user();
        
        if(!in_array($request->type,['mini','delivery','pickup'])) 
            return jsonOut(1,'Invalid order type.');

        if(in_array($request->type,['mini','delivery'])){ // check address 
            $address = \App\UserAddress::find($request->address);
            if(!$address || $address->user_id != $user->id)
                return jsonOut(1,'You need to have an address to order delivery.');
        }

        if(!in_array($request->time,['now','all']))
            return jsonOut(1,'Invalid order time.');

        switch($request->type){
            case 'mini':
                if($address->building){
                    $stores_building = $address->building->coverageStores()->acceptOrders()->with('city','area')->get();
                    if($request->time == 'now')
                        $stores_building = $stores_building->where('is_deliver_building','true');
                }else{
                    $stores_building = new \Illuminate\Database\Eloquent\Collection;
                }
                

                $stores_area = $address->area->coverageStores()->acceptOrders()
                                ->where('fee',0)->where('min',0)->where('feebelowmin',0)->with('city','area')->get();

                if($request->time == 'now')
                    $stores_area = $stores_area->where('is_deliver_area','true');
                
                $stores = $stores_area->merge($stores_building); // building will override area

                if($request->time == 'now')
                    $stores = $stores->where('status_working','open');


                break;



            case 'delivery': // this will never happen until adding the delivery for all areas


                if($address->building){
                    $stores_building = $address->building->coverageStores()->acceptOrders()->with('city','area')->get();
                    if($request->time == 'now')
                        $stores_building = $stores_building->where('is_deliver_building','true');
                }else{
                    $stores_building = new \Illuminate\Database\Eloquent\Collection;
                }
                

                $stores_area = $address->area->coverageStores()->acceptOrders()->with('city','area')->get();

                if($request->time == 'now')
                    $stores_area = $stores_area->where('is_deliver_area','true');
                
                $stores = $stores_area->merge($stores_building); // building will override area

                if($request->time == 'now')
                    $stores = $stores->where('status_working','open');


                break;






/*
                $stores = $address->building->coverageStores()->with('city','area')->get();
                $stores = $stores->merge($address->area->coverageStores()->with('city','area')->get());

                if($request->time == 'now'){
                    $stores = $stores->filter(function ($item) {
                        return ($item->is_deliver_area == 'true' || $item->is_deliver_building == 'true');
                    });
                }
                break;
*/


            case 'pickup':
                //check if area is set and is valid
                $area = \App\Area::find($request->area);
                if(!$area) return jsonOut(1,'Invalid area for pickup.');

                $stores = $area->stores()->acceptOrders()->with('city','area')->get();

                if($request->time == 'now'){
                    $stores = $stores->filter(function ($item) {
                        return ($item->is_open == 'true');
                    });
                }
                break;

        }

        return response()->json(compact('error', 'user','stores'));
    }



    public function orders()
    {
        $user = \Auth::user();
        return view('dashboard.orders', compact('user'));
    }


    //api
    public function getorders(Request $request)
    {
        $user = \Auth::user();
        $orders = \App\Order::where('user_id',$user->id)->where('hidden_user',0)->with('store','userAddress','userAddress.area','userAddress.building','paymentType')->get();

        $returnData = array(
            'error' => 0,
            'orders' => $orders
        );
        return response()->json($returnData);
    }


    public function orderUpdateStatus(Request $request, $orderId)
    {
        $user = \Auth::user();

        $order = \App\Order::find($orderId);

        if($order == null) return jsonOut('order_not_found','The order does not exists in our system.');

        if($order->user_id != $user->id) return jsonOut('order_denied','The order does not belong to you.');

        if($request->status != "" && $request->status != "callback" && $order->status != $request->status){
            $orderTime = new \App\OrderTime;
            $orderTime->user_id = $request->user()->id;
            $orderTime->order_id = $orderId;
            $orderTime->store_id = $order->store_id;
            $orderTime->status = $request->status;
            $orderTime->timestamp = time();
            $orderTime->save();
        }

        if($request->hide == 1) $order->hidden_user = 1;
        if($request->reason != "") $order->reason = $request->reason;
        if($request->status != ""){
            if ($request->status == "callback") $order->callback = 1;
            else  $order->status = $request->status;
        }

        $order->save();

        $orders = \App\Order::where('user_id',$user->id)->where('hidden_user',0)->with('store','userAddress','userAddress.area','userAddress.building','paymentType')->get();

        $returnData = array(
            'error' => 0,
            'orders' => $orders
        );
        return response()->json($returnData);
    }





    public function generalIndex()
    {
        $user = \Auth::user();
        return view('dashboard.general', compact('user'));
    }


    public function generalStore(Request $request)
    {
        $user = \Auth::user();

        $this->validate($request, [
            'username' => 'required|string|max:25|unique:stores,slug|unique:chains,slug',
            'name' => 'required|max:255',
            'mobile' => 'required|max:255',
            'gender' => 'size:1',
            'dob' => 'date',
        ]);

        if($user->username != $request->username){
            //check if user name is unique
            $checkUsername = \App\User::where('username', $request->username)->get();
            if($checkUsername->count() == 0){
                $user->username = $request->username;
            }else{
                return back()
                ->withErrors("Username is already taken.")
                ->withInput();
            }
            
        }

        if($user->mobile != $request->mobile)
            $user->verified_mobile = 0;

        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->dob = $request->dob;
        $user->mobile = $request->mobile;
        $user->save();

        if(\Session::get('newuser_profile', false) == true){
            \Session::put('newuser_profile', false);
            flash('Your profile saved successfully. Please add your address below.');
            return redirect('/dashboard/address/create');
        }

        flash('Your information updated successfully.');

        return redirect('/dashboard/general/');
    }



    public function dpStore(Request $request)
    {
        $user = \Auth::user();

        $this->validate($request, [
            'imagefile' => 'required|mimes:jpg,jpeg,png,bmp'
        ]);

        $file = $request->file('imagefile');

        //delete old 
        if($user->dp) \File::delete($this->dpBase. $user->dp); 
        if($user->dp) \File::delete($this->dpBaseTiny. $user->dp); 

        //store the new image
        $photoFileName = time() . '-' . str_random(10) . '.jpg' ;

        $image = \Image::make($file->getRealPath());
        $image->fit(150,150)->save($this->dpBase.$photoFileName);

        //do again for tiny
        $image = \Image::make($file->getRealPath());
        $image->fit(25,25)->save($this->dpBaseTiny.$photoFileName);

        //update db
        $user->dp = $photoFileName;
        $user->save();

        flash('Photo update successfully.');
        return redirect('/dashboard/general/');

    }





    public function passwordIndex()
    {
        $user = \Auth::user();

        return view('dashboard.password', compact('user'));
    }

    public function passwordStore(Request $request)
    {
        $user = \Auth::user();

        $this->validate($request, [
            'password' => 'required|min:6',
            'newpassword' => 'required|confirmed|min:6',
        ]);

        if (!\Hash::check($request->password, $user->password))
        {
            return back()
            ->withErrors("Current password is not matching our records.")
            ->withInput();
        }

        $user->password = $request->newpassword;
        $user->save();

        flash('Your password has been saved successfully.');

        return redirect('/dashboard/password');
    }










    public function addressIndex()
    {
        $user = \Auth::user();
        return view('dashboard.address.index', compact('user'));
    }

    public function addressCreate()
    {
        
        return view('dashboard.address.create', compact('user'));
    }

    public function addressStore(Request $request)
    {

        $this->validate($request, [
            'country' => 'required|max:2',
            'city_id' => 'required|integer',
            'area_id' => 'required|integer',
            // 'building_id' => 'required|integer',
            'unit' => 'required|string',
            'info' => 'string',
            'phone' => 'string',
            'name' => 'required|string'
        ]);

        $user = \Auth::user()->addresses()->create($request->all());

        if(\Session::get('newuser_address', false) == true){
            \Session::put('newuser_address', false);
            flash('Address created successfully. You can make your first order now.');
            return redirect('/dashboard/order');
        }

        flash('Address created successfully.');
        return redirect('/dashboard');
    }


    public function addressDestroy(Request $request, $addressId)
    {
        //delete the address 
        $address = \App\UserAddress::findOrFail($addressId);

        if($address->user_id != \Auth::user()->id) abort(403);

        $address->delete();

        flash('Address deleted successfully.');
        return redirect('/dashboard/address');
    }


    public function addressEdit(Request $request, $addressId)
    {
        //delete the address 
        $address = \App\UserAddress::findOrFail($addressId);

        if($address->user_id != \Auth::user()->id) abort(403);


        return view('dashboard.address.edit', compact('address'));
    }

    public function addressUpdate(Request $request, $addressId)
    {
        $this->validate($request, [
            'country' => 'required|max:2',
            'city_id' => 'required|integer',
            'area_id' => 'required|integer',
            'building_id' => 'integer',
            'unit' => 'required|string',
            'info' => 'string',
            'phone' => 'string',
            'name' => 'required|string'
        ]);

        $address = \App\UserAddress::findOrFail($addressId);

        if($address->user_id != \Auth::user()->id) abort(403);

        $address->fill($request->all());
        if(!$request->building_id) $address->building_id = null;

        $address->save();

        flash('Address updated successfully.');

        return redirect('/dashboard/address');
    }

   
}
