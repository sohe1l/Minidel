<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use \Carbon\Carbon;

class BrowseController extends Controller
{

    var $store_columns = ['stores.name','stores.slug','stores.country','stores.area_id','stores.city_id',
                        'stores.building_id','stores.status_working','stores.info',
                        'stores.logo','stores.cover']; // used in dashbaord.orderStores / browse.search

    protected $storeImageBase = 'img/store/';
    protected $storeImageBaseThumb = 'img/store-thumb/';


    public function browseIndex()
    {
        return view('browse.index');
    }


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

    public function building($citySlug, $areaSlug, $buildingSlug)
    {
        $city = \App\City::where('slug',$citySlug)->firstOrFail();
        $area = $city->areas()->where('slug',$areaSlug)->first();
        $building = $area->buildings()->where('slug',$buildingSlug)->firstOrFail();

        return view('browse.building', compact('city','area','building'));
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


/*
    public function store($citySlug, $areaSlug, $storeSlug)
    {
        $city = \App\City::where('slug',$citySlug)->firstOrFail();
        $area = $city->areas()->where('slug',$areaSlug)->firstOrFail();
        $store = $area->stores()->where('slug',$storeSlug)->with('building','sections.subsections.items','sections.items','payments')->firstOrFail();
        $user = \Auth::user();
                
        return view('browse.store', compact('city','area','store','user'));
    }
*/

    public function storeOrderInline($storeSlug){
        return $this->storeOrder($storeSlug, true);
    }

    public function storeOrder($storeSlug, $inline = false)
    {        
        $store = \App\Store::where('slug',$storeSlug)->with('building','sections.subsections.items','sections.items','payments','items.options.options')->firstOrFail();
        $user = \Auth::user();


        // get delivery days / time / min /...  PER LOCATION

        $user_addresses = array();
        if($user){
            foreach($user->addresses as $address){
                $itemBldg = null;
                $itemArea = null;
                $type = "";
                if($address->building_id && $store->coverageBuildings->contains('id', $address->building_id)){
                    $itemBldg = $store->coverageBuildings->where('id',$address->building_id)->first()->pivot;
                }
                if($store->coverageAreas->contains('id', $address->area_id)){
                    $itemArea = $store->coverageAreas->where('id',$address->area_id)->first()->pivot;
                }

                if($itemBldg != "" or $itemArea != ""){
                    $user_addresses[] = [
                        "text"          => $address->name,
                        "value"         => $address->id,
                        "bldg"          => $itemBldg,
                        "area"      => $itemArea
                    ];
                }
            }
        }

        $storeTimings = $store->timings()->where('workmode_id',1)->orWhere('workmode_id',2)->orWhere('workmode_id',5)->get();

        //dd($storeTimings);

        // $daysMini = $this->getDays($store, 'Building Delivery');
        // $daysDelivery = $this->getDays($store, 'Area Delivery');
        // $daysPickup = $this->getDays($store, 'Normal Openning');

        //dd($daysDelivery);
        //dd(array_unique(array_merge($daysMini[0]['deliveryTime'], $daysDelivery[0]['deliveryTime']),SORT_REGULAR ) );

        //dd($daysMini);

        $last_online = Carbon::parse($store->last_check)->diffForHumans();
        $is_online = Carbon::parse($store->last_check)->gt( Carbon::now()->subMinute() );

        $active_promos = $store->promos()->active()->first();

        if($inline) return view('browse.store-order-inline', compact('store','user','user_addresses','page_title','last_online','is_online','active_promos','storeTimings'));

        $page_title = $store->name . " Online Order Delivery & Pickup";
        return view('browse.store-order', compact('store','user','user_addresses','page_title','last_online','is_online', 'active_promos','storeTimings'));
    }


    public function storeOrderStore(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->firstOrFail();
        $user = \Auth::user();
      
        $schedule = json_decode($request->schedule);
        
        $response = saveOrder($store, $user, json_decode($request->cart), $request->payment, $request->totalPrice, $request->fee, $request->instructions, $request->dorp, $request->address, $schedule);

        if(is_array($response)) return response()->json($response);
        else return jsonOut(1,$response);  
    }




   public function storephoto(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->firstOrFail();
        $user = \Auth::user();

        if(!$user) return redirect("/auth/login?redirect=/".$store->slug."/reviews");

        //validate request
        $this->validate($request, [
            'imagefile' => 'required|mimes:jpg,jpeg,png,bmp',
            'photoCaption' => 'required|string|max:200'
        ]);


        $file = $request->file('imagefile');

        //store the new image
        $photoFileName = time() . '-' . str_random(5) . '-' . $store->slug . '.jpg' ;

        $image = \Image::make($file->getRealPath());
        $image->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->resize(null, 600, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($this->storeImageBase.$photoFileName);


        $image = \Image::make($file->getRealPath());
        $image->fit(140,140)->save($this->storeImageBaseThumb.$photoFileName);

        $store->photos()->create(['path'=>$photoFileName, 'text'=> $request->photoCaption, 'user_id'=>$user->id]);


        flash('Image uploaded successfully.');
    
        return redirect('/' . $storeSlug );
    }



   public function storeReviews($storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->firstOrFail();
        $user = \Auth::user();


        return view('browse.store-reviews', compact('store','user'));
    }

    public function storeReviewsStore(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->firstOrFail();
        $user = \Auth::user();

        if(!$user) return redirect("/auth/login?redirect=/".$store->slug."/reviews");

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

        return redirect('/' . $storeSlug );
    }






    public function search(Request $request)
    {
        $searchQuery = $request->q;

        $searchTerms = explode(' ', $searchQuery);

        $stores = \App\Store::listed()->acceptOrders()->where(function ($query) use ($searchTerms) {

            foreach($searchTerms as $term) $query->orWhere('name', 'LIKE', '%'. $term .'%');
        
        })->with('city','area')->get();

        return view('browse.search', compact('stores','searchQuery'));
    }

    public function searchPost(Request $request)
    {
        $searchQuery = $request->q;

        $searchTerms = explode(' ', $searchQuery);
        

        $arrTags = [];
        foreach(json_decode($request->tags) as $key => $val)
            if($val) $arrTags[] = $val; 

        $stores = \App\Store::listed()->acceptOrders();


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

        $stores = $stores->with('city','area')->get();
        
        $returnData = array(
            'error' => 0,
            'stores' => $stores
        );
        return response()->json($returnData);
    }

    public function store($storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->where('status_listing', 'published')->with('building','sections.subsections.items','sections.items','payments')->firstOrFail();
        $user = \Auth::user();
        return view('browse.store', compact('store','user'));     
    }



    public function chain($chainSlug)
    {
        $chain = \App\Chain::where('slug', $chainSlug)->firstOrFail();
        return view('browse.chain', compact('chain'));      
    }


    public function profile($username)
    {
        $user = \App\User::where('username', $username)->firstOrFail();
        return view('browse.profile', compact('user'));
    }





    public function promotions(){
        return view('browse.promotions', compact('store','user')); 
    }

    // public function profileOrStore($usernameOrSlug)
    // {

    //     $store = \App\Store::where('slug',$usernameOrSlug)->with('building','sections.subsections.items','sections.items','payments')->first();
    //     $user = \Auth::user();
    //     if($store) return view('browse.store', compact('store','user'));

    //     unset($user);

    //     $chain = \App\Chain::where('slug', $usernameOrSlug)->first();
    //     if($chain) return view('browse.chain', compact('chain'));


    //     $user = \App\User::where('username', $usernameOrSlug)->first();
    //     if($user) return view('browse.profile', compact('user'));


    //     abort(404);        
    // }



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
   

    private function getDaysCombine($store, $workmode){
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
