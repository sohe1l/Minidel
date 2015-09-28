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
            
        return view('dashboard.index', compact('user','merged'));
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

        if($order == null) return $this->jsonOut('order_not_found','The order does not exists in our system.');

        if($order->user_id != $user->id) return $this->jsonOut('order_denied','The order does not belong to you.');

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
