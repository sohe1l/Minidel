<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BrowseController extends Controller
{

    var $store_columns = ['stores.name','stores.slug','stores.country','stores.area_id','stores.city_id',
                        'stores.building_id','stores.status_working','stores.info',
                        'stores.logo','stores.cover']; // used in dashbaord.orderStores / browse.search

    public function city($citySlug)
    {
        $city = \App\City::where('slug',$citySlug)->firstOrFail();
        return view('browse.city', compact('city'));
    }

    public function area($citySlug, $areaSlug)
    {
        $city = \App\City::where('slug',$citySlug)->firstOrFail();
        $area = $city->areas()->where('slug',$areaSlug)->first();
        return view('browse.area', compact('city','area'));
    }



    public function deliveryBuilding($citySlug, $areaSlug, $buildingSlug)
    {
        $city = \App\City::where('slug',$citySlug)->firstOrFail();
        $area = $city->areas()->where('slug',$areaSlug)->firstOrFail();
        $building = $area->buildings()->where('slug',$buildingSlug)->firstOrFail();

        $buildingCoverage = $building->coverageStores;
        $areaCoverage = $area->coverageStores;

        $allStores = $buildingCoverage->merge($areaCoverage);

        return view('browse.delivery-building', compact('city','area','building','allStores'));
    }








    public function store($citySlug, $areaSlug, $storeSlug)
    {
        $city = \App\City::where('slug',$citySlug)->firstOrFail();
        $area = $city->areas()->where('slug',$areaSlug)->firstOrFail();
        $store = $area->stores()->where('slug',$storeSlug)->firstOrFail();
        $user = \Auth::user();


        return view('browse.store', compact('city','area','store','user'));
    }

    public function storeOrder($citySlug, $areaSlug, $storeSlug)
    {
        $city = \App\City::where('slug',$citySlug)->firstOrFail();
        $area = $city->areas()->where('slug',$areaSlug)->firstOrFail();
        $store = $area->stores()->where('slug',$storeSlug)->firstOrFail();
        $user = \Auth::user();


        // get delivery days / time / min /...  PER LOCATION

        $user_addresses = array();
        if($user){
            foreach($user->addresses as $address){
                $type = "";
                if($store->coverageBuildings->contains('id', $address->building_id)){
                    $item = $store->coverageBuildings->where('id',$address->building_id)->first();
                    $type = "mini";
                }else if($store->coverageAreas->contains('id', $address->area_id)){
                    $item = $store->coverageAreas->where('id',$address->area_id)->first();
                    $type = "delivery";

                }

                if($type != ""){
                    $user_addresses[] =  array(
                        "text"          => $address->name ,
                        "value"         => $address->id,
                        "fee"           => $item->pivot->fee,
                        "min"           => $item->pivot->min,
                        "feebelowmin"   => $item->pivot->feebelowmin,
                        "discount"      => $item->pivot->discount,
                        "type"          => $type);
                    $type = "";
                }
            }
        }


        $daysMini = $this->getDays($store, 'Building Delivery');
        $daysDelivery = $this->getDays($store, 'Area Delivery');
        $daysPickup = $this->getDays($store, 'Normal Openning');

        return view('browse.store-order', compact('city','area','store','user','user_addresses','daysMini','daysDelivery','daysPickup'));
    }


    public function storeOrderStore(Request $request, $citySlug, $areaSlug, $storeSlug)
    {
        
        $city = \App\City::where('slug',$citySlug)->firstOrFail();
        $area = $city->areas()->where('slug',$areaSlug)->firstOrFail();
        $store = $area->stores()->where('slug',$storeSlug)->firstOrFail();
        $user = \Auth::user();

        $response = saveOrder($store, $user, json_decode($request->cart), $request->totalPrice, $request->fee, $request->instructions, $request->dorp, $request->address, $request->showTimes, $request->day, $request->time);

        if($response == 'ok') return jsonOut(0,'order_saved');
        else return jsonOut(1,$response);   
    }






   public function storeReviews($citySlug, $areaSlug, $storeSlug)
    {
        $city = \App\City::where('slug',$citySlug)->firstOrFail();
        $area = $city->areas()->where('slug',$areaSlug)->firstOrFail();
        $store = $area->stores()->where('slug',$storeSlug)->firstOrFail();
        $user = \Auth::user();


        return view('browse.store-reviews', compact('city','area','store','user'));
    }

    public function storeReviewsStore(Request $request, $citySlug, $areaSlug, $storeSlug)
    {
        


        $city = \App\City::where('slug',$citySlug)->firstOrFail();
        $area = $city->areas()->where('slug',$areaSlug)->firstOrFail();
        $store = $area->stores()->where('slug',$storeSlug)->firstOrFail();
        $user = \Auth::user();

        if(!$user) return redirect("/auth/login?redirect=/store/".$city->slug."/".$area->slug."/".$store->slug."/reviews");

        //validate request
        $this->validate($request, [
            'public_review' => 'required|string',
            'private_review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $rating = new \App\Rating;
        $rating->store_id = $store->id;
        $rating->user_id = $user->id;
        $rating->public_review = $request->public_review;
        $rating->private_review = $request->private_review;
        $rating->rating = $request->rating;
        $rating->save();

        return redirect()->back();
    }






    public function search(Request $request)
    {
        $searchQuery = $request->q;

        $searchTerms = explode(' ', $searchQuery);
        $stores = new \App\Store;
        foreach($searchTerms as $term) $stores = $stores->orWhere('name', 'LIKE', '%'. $term .'%');
        $stores = $stores->with('city','area')->select($this->store_columns)->get();

        return view('browse.search', compact('stores','searchQuery'));
    }

    public function searchPost(Request $request)
    {
        $searchQuery = $request->searchQuery;

        $searchTerms = explode(' ', $searchQuery);
        $stores = new \App\Store;

        $arrTags = [];
        foreach(json_decode($request->tags) as $key => $val)
            if($val) $arrTags[] = $val; 

        if(count($arrTags) != 0){
            foreach($arrTags as $tagid){
              $stores = $stores->whereHas('tags', function ($query) use ($tagid) {
                    $query->where('id', $tagid);
                });
            }
        }

        $stores = $stores->where(function ($query) use($searchTerms) {
            foreach($searchTerms as $term){
                $query->orWhere('name', 'LIKE', '%'. $term .'%');
            }
        });

        $stores = $stores->with('city','area')->select($this->store_columns)->get();
        
        $returnData = array(
            'error' => 0,
            'stores' => $stores
        );
        return response()->json($returnData);
    }

    



    private function getDays($store, $workmode){
        $days = array();
        for($day = \Carbon\Carbon::now(); \Carbon\Carbon::now()->addDays(4) > $day; $day=$day->addDay()){
            $dayLetter = strtolower(date('D',$day->timestamp));
            $dayText = date('D M j Y',$day->timestamp);

            $timeArray = array();
            foreach($store->timingsSpecific($workmode,$dayLetter) as $timing){
                $record = 0;
                foreach(\Config::get('vars.timeList') as $keyTimelist => $valTimelist){
                    if($keyTimelist == $timing->start) $record = 1;

                    $loopTs = strtotime("$dayText $keyTimelist");
                    
                    if($loopTs  > time()){
                        //$output[] = ["$dayText $keyTimelist", $loopTs , $keyTimelist];
                        if($record) $timeArray[] = array("value"=>$keyTimelist, "text"=> $valTimelist);
                    }

                    if($keyTimelist == $timing->end) $record = 0;
                }
            }
            $days[] = array('value'=>$dayLetter, 'text'=> date('l',$day->timestamp), 'deliveryTime'=> $timeArray); // 
        }
        return $days;
    }
   
}
