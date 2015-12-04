<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.index');
    }



    public function statsIndex()
    {
        $date = new \DateTime('tomorrow -1 month');
        // lists() does not accept raw queries,
        // so you have to specify the SELECT clause
        $days = \App\Order::select(array(
                \DB::raw('DATE(`created_at`) as `date`'),
                \DB::raw('COUNT(*) as `count`')
            ))
            ->where('created_at', '>', $date)
            ->groupBy('date')
            ->orderBy('date')
            ->lists('count', 'date');

        return view('admin.stats.index',compact('days'));
    }



    public function statsTimesIndex()
    {
        $times = \App\OrderTime::paginate(100);

        return view('admin.stats.times',compact('times'));
    }




    public function storesIndex()
    {
        $stores = \App\Store::paginate(10);
        return view('admin.stores.index',compact('stores'));
    }



    public function storesUpdateStatus(Request $request)
    {
        //validate request
        $this->validate($request, [
            'store_id' => 'required|integer',
            'status_listing' => 'required|in:published,draft,review'
        ]);

        $store = \App\Store::findOrFail($request->store_id);
        $store->status_listing = $request->status_listing;
        $store->save();

        return redirect('/superadmin/stores/');
    }



    public function ordersIndex()
    {
        $orders = \App\Order::orderBy('id', 'desc')->paginate(100);
        return view('admin.orders.index',compact('orders'));
    }



    public function ordersPendingIndex()
    {
        return view('admin.orders.pending');
    }


    public function ordersGetPending(Request $request)
    {

        $orders = \App\Order::where('status','pending')->with('store', 'user','userAddress','userAddress.area','userAddress.building','paymentType')->get();

        $returnData = array(
            'error' => 0,
            'orders' => $orders,
        );
        return response()->json($returnData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
