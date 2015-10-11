<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        
        $merged = new \Illuminate\Database\Eloquent\Collection;

        foreach( $user->addresses  as $address){
            $building = \App\Building::find($address->building_id);
            $merged = $merged->merge($building->coverageStores);
        }


        $recent = $user->orders()->groupby('store_id')->take(5)->get(); // ->where('status','delivered')

        return view('dashboard.index', compact('user','merged','recent'));
    }

    public function orderRegular(Request $request){
        $user = \Auth::user();

        $oo = \App\Order::find($request->order);
        if(!$oo) return jsonOut(1,'Order does not exists.');

        $store = \App\Store::find($oo->store_id);
        if(!$store) return jsonOut(1,'Store does not exists.');

        $response = saveOrder($store, $user, json_decode($oo->cart), $oo->price, $oo->fee, $oo->instructions, $oo->type, $oo->user_address_id, $oo->schedule, $oo->day, $oo->time);

        if($response == 'ok') return jsonOut(0,'order_saved');
        else return jsonOut(1,$response);  

    }


    public function order()
    {
        $user = \Auth::user();

        $merged = new \Illuminate\Database\Eloquent\Collection;

        foreach( $user->addresses  as $address){
            $building = \App\Building::find($address->building_id);
            $merged = $merged->merge($building->coverageStores);
        }
            
        return view('dashboard.order', compact('user','merged'));
    }


    public function orderStores(Request $request)
    {
        //$store = \App\Store::find(1);
        //dd($store->append('is_open'));

        $error = 0;
        $user = \Auth::user();
        
        if(!in_array($request->type,['mini','pickup'])) //,'delivery'
            return jsonOut(1,'Invalid order type.');

        if(in_array($request->type,['mini'])){ // check address //,'delivery'
            $address = \App\UserAddress::find($request->address);
            if(!$address || $address->user_id != $user->id)
                return jsonOut(1,'You need to have an address to order delivery.');
        }

        if(!in_array($request->time,['now','all']))
            return jsonOut(1,'Invalid order time.');

        switch($request->type){
            case 'mini':
                $stores_building = $address->building->coverageStores()->with('city','area')->get();
                if($request->time == 'now')
                    $stores_building = $stores_building->where('is_deliver_building','true');
                

                $stores_area = $address->area->coverageStores()
                                ->where('fee',0)->where('min',0)->where('feebelowmin',0)->with('city','area')->get();

                if($request->time == 'now')
                    $stores_area = $stores_area->where('is_deliver_area','true');
                
                $stores = $stores_area->merge($stores_building); // building will override area

                if($request->time == 'now')
                    $stores = $stores->where('status_working','open');


                break;



            case 'delivery': // this will never happen until adding the delivery for all areas
                $stores = $address->building->coverageStores()->with('city','area')->get();
                $stores = $stores->merge($address->area->coverageStores()->with('city','area')->get());

                if($request->time == 'now'){
                    $stores = $stores->filter(function ($item) {
                        return ($item->is_deliver_area == 'true' || $item->is_deliver_building == 'true');
                    });
                }
                break;


            case 'pickup':
                //check if area is set and is valid
                $area = \App\Area::find($request->area);
                if(!$area) return jsonOut(1,'Invalid area for pickup.');

                $stores = $area->stores()->with('city','area')->get();

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
        $orders = \App\Order::where('user_id',$user->id)->where('hidden_user',0)->with('store','userAddress','userAddress.area','userAddress.building')->get();

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

        if($request->hide == 1) $order->hidden_user = 1;
        if($request->reason != "") $order->reason = $request->reason;
        if($request->status != ""){
            if ($request->status == "callback") $order->callback = 1;
            else  $order->status = $request->status;
        }

        $order->save();

        $orders = \App\Order::where('user_id',$user->id)->where('hidden_user',0)->with('store','userAddress','userAddress.area','userAddress.building')->get();

        $returnData = array(
            'error' => 0,
            'orders' => $orders
        );
        return response()->json($returnData);
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
            'building_id' => 'required|integer',
            'unit' => 'required|string',
            'info' => 'string',
            'phone' => 'string',
            'name' => 'required|string'
        ]);

        $user = \Auth::user()->addresses()->create($request->all());

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
            'building_id' => 'required|integer',
            'unit' => 'required|string',
            'info' => 'string',
            'phone' => 'string',
            'name' => 'required|string'
        ]);

        $address = \App\UserAddress::findOrFail($addressId);

        if($address->user_id != \Auth::user()->id) abort(403);

        $address->fill($request->all());

        $address->save();

        flash('Address updated successfully.');

        return redirect('/dashboard/address');
    }

   
}
