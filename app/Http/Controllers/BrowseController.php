<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BrowseController extends Controller
{
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

        $daysDelivery = $this->getDays($store, 'Building Delivery');
        $daysPickup = $this->getDays($store, 'Normal Openning');

        return view('browse.store', compact('city','area','store','user','daysDelivery','daysPickup'));
    }


    public function storeOrder(Request $request, $citySlug, $areaSlug, $storeSlug)
    {
        
        $city = \App\City::where('slug',$citySlug)->firstOrFail();
        $area = $city->areas()->where('slug',$areaSlug)->firstOrFail();
        $store = $area->stores()->where('slug',$storeSlug)->firstOrFail();
        $user = \Auth::user();

        //$daysDelivery = $this->getDays($store, 'Building Delivery');
        //$daysPickup = $this->getDays($store, 'Normal Openning');

        if(! (count($request->cart) > 0)) return $this->jsonOut(1,"No items in your order.");


        $order = new \App\Order;
        $order->user_id = $user->id;
        $order->store_id = $store->id;
        $order->status = 'pending';
        $order->instructions = $request->instructions;
        $order->cart = json_encode($request->cart);

        if(preg_match("/(delivery|pickup)/", $request->dorp) != 1) return $this->jsonOut(1,"Please either choose delivery or pickup.");
        $order->type = $request->dorp;

        $workmode = ""; // set the work mode for time function
        if($request->dorp == 'delivery'){
            $workmode = "Building Delivery";
            $address_exists = 0;
            foreach($user->listAddressesByStore($store->id) as $address){
                if($address['value'] == $request->address) $address_exists = 1;
            }
            if(!$address_exists) return $this->jsonOut(1,'Invalid delivery address.');
            $order->user_address_id = $request->address;
        }else{
            $workmode = "Normal Openning";
        }

        if($request->showTimes == "true"){
            //make sure time is in the future
            if(preg_match("/(sun|mon|tue|wed|thu|fri|sat)/", $request->day) != 1) return $this->jsonOut(1,'Invalid delivery day.');
            if(preg_match("/(2[0-4]|[01][1-9]|10):([0-5][0-9]:00)/", $request->time) != 1) return $this->jsonOut(1,'Invalid delivery time.');
            $schedule = strtotime($request->day . " " .  $request->time);
            if($schedule < time()) return $this->jsonOut(1,'Scheduled delivery cannot be in the past.');
            $order->schedule = date('Y-m-d H:i:s', $schedule);

            if($store->isOpenAtTimestamp($workmode, $schedule) != "true") return $this->jsonOut(1,'Store is not open for '. $request->dorp . ' at the specified time.');
        }else{
            if($store->isOpenNow($workmode) != "true") return $this->jsonOut(1,'Store is not open for '. $request->dorp);
        }

        if(!($request->totalPrice > 0)) return $this->jsonOut(1,'Total price cannot be zero.');
        $order->price = $request->totalPrice;


        $order->save();

        return $this->jsonOut(0,'order_saved');
    }



    private function jsonOut($error, $msg){
        $returnData = array(
            'error' => $error,
            'message' => $msg
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
