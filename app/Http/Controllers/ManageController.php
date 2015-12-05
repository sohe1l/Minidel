<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;

use Illuminate\Http\Request;

use Illuminate\Support\Str;

use GuzzleHttp\Client;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ManageController extends Controller
{


    protected $itemBase = 'img/item/';
    protected $itemBaseThumb = 'img/item-thumb/';

    protected $logoBase = 'img/logo/';
    protected $coverBase = 'img/cover/';
    protected $coverMobileBase = 'img/cover-mobile/';
   
    protected $menuImageBase = 'img/menu/';
    protected $menuImageBaseThumb = 'img/menu-thumb/';

    public function __construct()
    {
        /*
        $this->middleware('auth');

        $this->middleware('log', ['only' => ['fooAction', 'barAction']]);

        $this->middleware('Auth', ['except' => ['fooAction', 'barAction']]);
        */
    }


    public function index(Request $request)
    {
        $user = \App\User::find(Auth::user()->id);

        return view('manage.index',compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $countries = \Countries::getList('en', 'php', 'cldr');
        return view('manage.create', compact('countries'));
    }

    public function store(Request $request)
    {
        //validate request
        $this->validate($request, [
            'name' => 'required|max:100',
            'phone' => 'required|max:100',
            'email' => 'required|email',
            'store_url' =>  'required|alpha_dash|max:25|unique:users,username|unique:stores,slug|unique:chains,slug',
            'type' => 'required'
        ]);

        // save the store into the db
        $store = new \App\Store;
        $store->type = $request->type;
        $store->name = $request->name;
        $store->slug = $request->store_url;
        $store->phone = $request->phone;
        $store->email = $request->email;
        $store->country = $request->country;
        $store->city_id = $request->city;
        $store->area_id = $request->area;
        $store->building_id = $request->building;
        $store->save();

        // add the role for the user
        $role = \App\Role::whereName('store_owner')->first();
        $role->users()->attach($request->user()->id, ['store_id'=>$store->id]);

        \Session::put('hasRole', true);

        return redirect('/manage/');
    }








    public function show(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if($store->userRole($request->user()->id)==null) abort(403);

        return view('manage.show',compact('store'));
    }




    public function submitReview(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        $store->status_listing = 'review';
        $store->save();

        return redirect()->back();
    }









    public function reportsIndex(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        //create empty array
        $orders = [];
        $price = [];
        
        $dt = Carbon::now()->subDays(31);
        for($i = 0; $i<30; $i++){
            $orders[$dt->format('M j')] = 0;
            $price[$dt->format('M j')] = 0;
            $dt->addDay();
        }

        //get db data
        $daily = $store->orders()
                ->where('status','delivered')
                ->whereBetween('created_at', [Carbon::now()->subDays(31), Carbon::now()])
                ->select(\DB::raw('date_format(created_at,"%b %e") as day'),
                         \DB::raw('sum(price) as total'), 
                         \DB::raw('count(*) AS count')
                    )
                ->groupBy('day')
                ->get();

        //fill array with db data
        foreach($daily as $day){
            $orders[$day->day] = $day->count;
            $price[$day->day] = $day->total;
        }


        return view('manage.reports.index',compact('store','orders','price'));
    }


    public function orders(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        $monthlyBreakdown = $store->orders()
                ->where('status','delivered')
                ->select(\DB::raw('date_format(created_at,"%Y/%c") as yearMonth'), \DB::raw('sum(price) as total'), 
                         \DB::raw('sum(CASE WHEN type = "delivery" THEN price ELSE 0 END) AS delivery'),
                         \DB::raw('sum(CASE WHEN type = "pickup" THEN price ELSE 0 END) AS pickup'),
                         \DB::raw('sum(CASE WHEN type = "pickup" THEN 1 ELSE 0 END) AS countPickup'),
                         \DB::raw('sum(CASE WHEN type = "delivery" THEN 1 ELSE 0 END) AS countDelivery'),
                         \DB::raw('count(*) AS count')
                    )
                ->groupBy('yearMonth')
                ->get();

        return view('manage.reports.orders',compact('store','monthlyBreakdown'));
    }


    public function ordersMonth(Request $request, $storeSlug, $year, $month)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        $orders = $store->orders()
                ->whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', $year)
                ->get();



        return view('manage.reports.orders-month',compact('store','orders','year','month'));
    }



    public function orderShow(Request $request, $storeSlug, $orderId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        $order = $store->orders()->find($orderId);
        if(!$order) abort(404);


        return view('manage.reports.order',compact('store', 'order'));
    }


    public function billing(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        $monthlyBreakdown = $store->transactions()
        ->select(\DB::raw('date_format(created_at,"%Y/%c") as yearMonth'),
                 \DB::raw('sum(CASE WHEN type = "credit" THEN amount ELSE 0 END) AS credit'),
                 \DB::raw('sum(CASE WHEN type = "debit" THEN amount ELSE 0 END) AS debit'),
                 \DB::raw('sum(CASE WHEN type = "credit" THEN 1 ELSE 0 END) AS countCredit'),
                 \DB::raw('sum(CASE WHEN type = "debit" THEN 1 ELSE 0 END) AS countDebit')            )
        ->groupBy('yearMonth')
        ->get();

        return view('manage.reports.billing',compact('store','monthlyBreakdown'));
    }

    public function billingMonth(Request $request, $storeSlug, $year, $month)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        $transactions = $store->transactions()
                        ->whereMonth('created_at', '=', $month)
                        ->whereYear('created_at', '=', $year)
                        ->get();

        return view('manage.reports.billing-month',compact('store','transactions','year','month'));
    }


    public function billingCreateTransaction(Request $request, $storeSlug){ // add transaction ONLY SUPER ADMIN
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array(\Auth::user()->id, \Config::get('vars.superAdmins'))) abort(403); // SUPERADMIN



        $this->validate($request, [
            'amount' => 'required|numeric',
            'type' => 'required|in:debit,credit',
            'reference' => 'required|max:200',
        ]);

        $tran = new \App\Transaction;
        $tran->user_id = $request->user()->id;
        $tran->store_id = $store->id;
        $tran->amount = $request->amount;
        $tran->type = $request->type;
        $tran->reference = $request->reference ;
        $tran->save();
        
        flash("Transaction created successfully.");

        return redirect()->back();        

    }
















    public function general(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        

        $countries = \Countries::getList('en', 'php', 'cldr');
        return view('manage.general',compact('store', 'countries'));
    }

    public function generalStore(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        //validate request
        $this->validate($request, [
            'name' => 'required|max:100',
            'phone' => 'required|max:100',
            'email' => 'required|email',
            'info' => 'required',
            'status_working' => 'required|in:open,close,busy',
            'accept_orders' => 'required|boolean',
        ]);

        // save the store into the db
        $store->name = $request->name;
        $store->phone = $request->phone;
        $store->email = $request->email;
        $store->info = $request->info;
        $store->status_working = $request->status_working;
        $store->accept_orders = $request->accept_orders;
        $store->save();

        flash('Store information updated successfully.');
        return redirect('/manage/' . $storeSlug . '/general');
    }





    public function logoStore(Request $request, $storeSlug)
    {        
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        //validate request
        $this->validate($request, [
            'imagefile' => 'required|mimes:jpg,jpeg,png,bmp'
        ]);


        $file = $request->file('imagefile');

        //delete old logo
        if($store->logo) \File::delete($this->logoBase. $store->logo); 

        //store the new image
        $photoFileName = time() . '-' . $store->slug . '-' . str_random(2) . '.jpg' ;

        $image = \Image::make($file->getRealPath());

        // prevent possible upsizing
        $image->resize(150, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });


        $image->save($this->logoBase.$photoFileName);

        //update db
        $store->logo = $photoFileName;
        $store->save();

        flash('Logo update successfully.');
        return redirect('/manage/' . $storeSlug . '/general');
    }

   public function coverStore(Request $request, $storeSlug)
    {        
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        //validate request
        $this->validate($request, [
            'imagefile' => 'required|mimes:jpg,jpeg,png,bmp'
        ]);


        $file = $request->file('imagefile');

        //delete old 
        if($store->cover) \File::delete($this->coverBase. $store->cover); 
        if($store->cover) \File::delete($this->coverMobileBase. $store->cover); 

        //store the new image
        $photoFileName = time() . '-' . $store->slug . '-' . str_random(2) . '.jpg' ;
        $image = \Image::make($file->getRealPath());
        $image->fit(960,320)->save($this->coverBase.$photoFileName);
        //do again for mobile
        $image = \Image::make($file->getRealPath());
        $image->fit(320,180)->save($this->coverMobileBase.$photoFileName);

        //update db
        $store->cover = $photoFileName;
        $store->save();

        flash('Cover update successfully.');
        return redirect('/manage/' . $storeSlug . '/general' );
    }





    public function inline(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager' , 'store_staff'])) abort(403);
        
        return view('manage.inline',compact('store'));
    }




    public function location(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        if($store->coordinate){
            list($lat,$lng)=explode(',', $store->coordinate);
        }else{
            $lat = '25.2048';
            $lng = '55.2708';
        }

        $countries = \Countries::getList('en', 'php', 'cldr');
        return view('manage.location',compact('store', 'countries','lat','lng'));
    }



    public function locationStore(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        //validate request
        $this->validate($request, [
            'country' => 'required|max:2',
            'city_id' => 'required|integer',
            'area_id' => 'required|integer',
            'building_id' => 'integer'
        ]);

        // save the store into the db
        $store->country = $request->country;
        $store->city_id = $request->city_id;
        $store->area_id = $request->area_id;
        $store->building_id = $request->building_id;
        $store->save();

        flash('Store location updated successfully.');
        return redirect('/manage/' . $storeSlug . '/location');
    }



    public function locationMarkerStore(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        //validate request
        $this->validate($request, [
            'lat' => 'required',
            'lng' => 'required'
        ]);

        // save the store into the db
        $store->coordinate = $request->lat . ',' . $request->lng;
        $store->save();

        flash('Store location marker updated successfully.');
        return redirect('/manage/' . $storeSlug . '/location');
    }











    public function menu(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->with('sections.subsections.items','sections.items')->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        return view('manage.menu',compact('store'));
    }

    public function menuSectionStore(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        //validate request
        $this->validate($request, [
            'title' => 'required|max:100',
            'parent' => 'integer'
        ]);

        $section = new \App\menuSection;
        $section->store_id = $store->id;
        $section->title = $request->title;
        
        if($request->parent != ""){
            $sectionCheck = \App\menuSection::where('id',$request->parent)->first();
            if($sectionCheck == null || $sectionCheck->store_id != $store->id){
                flash('Parent section does not belong to the store.');
                return redirect('/manage/' . $storeSlug . '/menu' );
            }
            $section->menu_section_id = $request->parent;
            $section->order = $store->lastSectionOrder($request->parent) + 1;
        }else{
          $section->order = $store->lastSectionOrder() + 1;  
        }

        

        $section->save();
        
        flash('Menu section created successfully.');
        return redirect('/manage/' . $storeSlug . '/menu' );
    }

    public function menuSectionDelete(Request $request, $storeSlug, $sectionId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        $section = \App\menuSection::findOrFail($sectionId);

        if($section->store_id != $store->id) abort(403);

        foreach($section->subsections as $subsection){
            $subsection->delete();
        }

        $section->delete();

        flash('Menu section deleted successfully.');
        return redirect('/manage/' . $storeSlug . '/menu' );
    }








    public function menuSectionUp(Request $request, $storeSlug, $sectionId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        $item = \App\menuSection::findOrFail($sectionId);
        if($store->id != $item->store_id) abort(403); // item belongs to someone else

        if($item->order != 0){
            $item->order = $item->order - 1;

            $item2 = \App\menuSection::where('order', $item->order)->where('menu_section_id',$item->menu_section_id)->first();

            if($item2  != null){
                $item2->order = $item2->order + 1;
                $item2->save();
            }

            $item->save();
        }
        

        flash('Menu section updated successfully.');
        return redirect('/manage/' . $storeSlug . '/menu' );
    }

    public function menuSectionDown(Request $request, $storeSlug, $sectionId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        $item = \App\menuSection::findOrFail($sectionId);
        if($store->id != $item->store_id) abort(403); // item belongs to someone else


        if($item->order != $store->lastSectionOrder($item->menu_section_id)){
            $item->order = $item->order + 1;

            $item2 = \App\menuSection::where('order', $item->order)->where('menu_section_id',$item->menu_section_id)->first();
                                  
            if($item2  != null){
                $item2->order = $item2->order - 1;
                $item2->save();
            }

            $item->save();
        }
        

        flash('Menu section updated successfully.');
        return redirect('/manage/' . $storeSlug . '/menu' );
    }

    public function menuSectionUpdateAvailable(Request $request, $storeSlug, $sectionId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        $item = \App\menuSection::findOrFail($sectionId);
        if($store->sections()->get()->where('id',$item->id)->isEmpty()) abort(403); // item belongs to someone else


        //validate request
        $this->validate($request, [
            'available' => 'required|integer|min:0|max:1'
        ]);

        $item->available = $request->available;
        $item->save();
        
        flash('Section update successfully.');
        return redirect('/manage/' . $storeSlug . '/menu' );
    }




































    public function menuItemCreate(Request $request, $storeSlug, $sectionId)
    {
        $store = \App\Store::where('slug',$storeSlug)->with('sections.subsections')->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        $section = \App\menuSection::findOrFail($sectionId);
        if($store->sections()->get()->where('id',$section->id)->isEmpty()) abort(403); // item belongs to someone else

        return view('manage.menu-item',compact('store', 'section'));
    }

    public function menuItemStore(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        //validate request
        $this->validate($request, [
            'menu_section_id' => 'required|integer',
            'title' => 'required|max:30',
            'info' => 'max:200',
            'price' => 'required|numeric',
            'imagefile' => 'mimes:jpg,jpeg,png,bmp',
        ]);


        //dd($_POST['options']);
        //dd($request->options);

        $section = \App\menuSection::findOrFail($request->menu_section_id);
        if($section->store_id != $store->id) abort(403);

        $item = $section->items()->create($request->all());

        if(is_array($request->options)) $item->options()->sync($request->options);

        if(is_array($request->tags)) $item->tags()->sync($request->tags);

        //handle image
        $file = $request->file('imagefile');
        if($file){
            //delete the old picture
            //if($item->photo) \File::delete($this->menuBase.$item->photo);

            //store the new image
            $photoFileName = time() . '-menu-' . $store->slug . '-' . str_random(2) . '.jpg' ;
            
            $image = \Image::make($file->getRealPath());
            $image->fit(500,500)->save($this->itemBase.$photoFileName);

            $image = \Image::make($file->getRealPath());
            $image->fit(100,100)->save($this->itemBaseThumb.$photoFileName);

            //update db
            $item->photo = $photoFileName;
            $item->save();
        }


        flash('Item created successfully.');
        if($request->goback == 1)
            return redirect('/manage/' . $storeSlug . '/menu' );
        else
            return redirect('/manage/' . $storeSlug . '/menu/section/' . $request->menu_section_id . '/item/create/' );
    }

    public function menuItemDestroy(Request $request, $storeSlug, $itemId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        $item = \App\menuItem::findOrFail($itemId);

        if($store->items()->get()->where('id',$item->id)->isEmpty()) abort(403); // item belongs to someone else

        // delete photo
        if($item->photo){
            \File::delete($this->itemBase.$item->photo);
            \File::delete($this->itemBaseThumb.$item->photo);
        } 


        $item->delete();

        flash('Menu item deleted successfully.');
        return redirect('/manage/' . $storeSlug . '/menu' );
    }



    public function menuItemEdit(Request $request, $storeSlug, $itemId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        $item = \App\menuItem::findOrFail($itemId);
        if($store->items()->get()->where('id',$item->id)->isEmpty()) abort(403); // item belongs to someone else

        //$sectionList = $store->sections()->lists('title', 'id');

        return view('manage.menu-item-edit',compact('store','item'));
    }


    public function menuItemUpdate(Request $request, $storeSlug, $itemId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        $item = \App\menuItem::findOrFail($itemId);
        if($store->items()->get()->where('id',$item->id)->isEmpty()) abort(403); // item belongs to someone else

        //validate request
        $this->validate($request, [
            'menu_section_id' => 'required|integer',
            'title' => 'required|max:30',
            'info' => 'max:200',
            'price' => 'required|numeric',
            'imagefile' => 'mimes:jpg,jpeg,png,bmp'
        ]);

        if($request->menu_section_id != $item->menu_section_id){ //change order as well
            $item->menu_section_id = $request->menu_section_id;
            $item->order = $item->lastOrder() + 1; // first update section then get lastOrder
            //TODO: fix order in the old section as well
        } 
        
        $item->title = $request->title;
        $item->info = $request->info;
        $item->price = $request->price;
        $item->save();
        
        //handle options
        if(is_array($request->options)){
            $item->options()->sync($request->options);
        }else{
            $item->options()->detach();
        }

        if(is_array($request->tags)){
            $item->tags()->sync($request->tags);
        }else{
            $item->tags()->detach();
        }



        //handle image
        $file = $request->file('imagefile');
        if($file){
            //delete the old picture
            if($item->photo){
                \File::delete($this->itemBase.$item->photo);
                \File::delete($this->itemBaseThumb.$item->photo);
            } 

            //store the new image
            $photoFileName = time() . '-menu-' . $store->slug . '-' . str_random(2) . '.jpg' ;

            $image = \Image::make($file->getRealPath());
            $image->fit(500,500)->save($this->itemBase.$photoFileName);

            $image = \Image::make($file->getRealPath());
            $image->fit(100,100)->save($this->itemBaseThumb.$photoFileName);

                        //update db
            $item->photo = $photoFileName;
            $item->save();
        }





        flash('Menu item update successfully.');
        return redirect('/manage/' . $storeSlug . '/menu' );
    }

    public function menuItemUpdateAvailable(Request $request, $storeSlug, $itemId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        $item = \App\menuItem::findOrFail($itemId);
        if($store->items()->get()->where('id',$item->id)->isEmpty()) abort(403); // item belongs to someone else


        //validate request
        $this->validate($request, [
            'available' => 'required|integer|min:0|max:1'
        ]);

        $item->available = $request->available;
        $item->save();
        
        flash('Menu item update successfully.');
        return redirect('/manage/' . $storeSlug . '/menu' );
    }





    public function menuItemUp(Request $request, $storeSlug, $itemId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        $item = \App\menuItem::findOrFail($itemId);
        if($store->items()->get()->where('id',$item->id)->isEmpty()) abort(403); // item belongs to someone else

        if($item->order != 0){
            $item->order = $item->order - 1;

            $item2 = \App\menuItem::where('menu_section_id',$item->menu_section_id)
                                  ->where('order', $item->order)->first();

            if($item2  != null){
                $item2->order = $item2->order + 1;
                $item2->save();
                flash('item2 saved: ' . $item2->order);
            }

            $item->save();
        }
        

        flash('Menu item update successfully.');
        return redirect('/manage/' . $storeSlug . '/menu' );
    }

    public function menuItemDown(Request $request, $storeSlug, $itemId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        $item = \App\menuItem::findOrFail($itemId);
        if($store->items()->get()->where('id',$item->id)->isEmpty()) abort(403); // item belongs to someone else

        if($item->order != $item->lastOrder()){
            $item->order = $item->order + 1;

            $item2 = \App\menuItem::where('menu_section_id',$item->menu_section_id)
                                  ->where('order', $item->order)->first();
                                  
            if($item2  != null){
                $item2->order = $item2->order - 1;
                $item2->save();
            }

            $item->save();
        }
        

        flash('Menu item update successfully.');
        return redirect('/manage/' . $storeSlug . '/menu' );
    }




















    public function options(Request $request, $storeSlug)
    {        
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        return view('manage.options',compact('store'));
    }


    public function optionsCreate(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        return view('manage.options-create',compact('store'));
    }


    public function optionsStore(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        //validate request
        $this->validate($request, [
            'name' => 'required|max:30',
            'min' => 'required|integer',
            'max' => 'required|integer',

            'name1' => 'required|max:30', 'name2' => 'max:30', 'name3' => 'max:30', 'name4' => 'max:30', 'name5' => 'max:30',
            'name6' => 'max:30', 'name7' => 'max:30', 'name8' => 'max:30', 'name9' => 'max:30', 'name10' => 'max:30',
            'name11' => 'max:30', 'name12' => 'max:30', 'name13' => 'max:30', 'name14' => 'max:30', 'name15' => 'max:30',
            'name16' => 'max:30', 'name17' => 'max:30', 'name18' => 'max:30', 'name19' => 'max:30', 'name20' => 'max:30',

            'price1' => 'numeric', 'price2' => 'numeric', 'price3' => 'numeric', 'price4' => 'numeric', 'price5' => 'numeric',
            'price6' => 'numeric', 'price7' => 'numeric', 'price8' => 'numeric', 'price9' => 'numeric', 'price10' => 'numeric',
            'price11' => 'numeric', 'price12' => 'numeric', 'price13' => 'numeric', 'price14' => 'numeric', 'price15' => 'numeric',
            'price16' => 'numeric', 'price17' => 'numeric', 'price18' => 'numeric', 'price19' => 'numeric', 'price20' => 'numeric',

        ]);


        // create the option
        $option = new \App\menuOption;
        $option->store_id = $store->id;
        $option->name = $request->name;
        $option->min = $request->min;
        $option->max = $request->max;
        $option->save();

        for($i = 1; $i<21; $i++){
            if($request['name'.$i] != ""){
                $options = new \App\menuOptionOption;
                $options->menu_option_id = $option->id;
                $options->name = $request['name'.$i];
                $options->price = $request['price'.$i];
                $options->save();
            }
        }

        flash('Option created successfully.');
        return redirect('/manage/' . $storeSlug . '/options' );
    }


    public function optionsDestroy(Request $request, $storeSlug, $optionId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        $option = \App\menuOption::findOrFail($optionId);

        if($option->store_id != $store->id) abort(403); // item belongs to someone else

        $option->delete();

        flash('Option deleted successfully.');
        return redirect('/manage/' . $storeSlug . '/options' );
    }



    public function optionsEdit(Request $request, $storeSlug, $optionId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        $option = \App\menuOption::findOrFail($optionId);

        if($option->store_id != $store->id) abort(403); // item belongs to someone else

        return view('manage.options-edit',compact('store', 'option'));
    }


    public function optionsUpdate(Request $request, $storeSlug, $optionId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        $option = \App\menuOption::findOrFail($optionId);

        if($option->store_id != $store->id) abort(403); // item belongs to someone else

        //validate request
        $this->validate($request, [
            'name' => 'required|max:30',
            'min' => 'required|integer',
            'max' => 'required|integer',

            'name1' => 'required|max:30', 'name2' => 'max:30', 'name3' => 'max:30', 'name4' => 'max:30', 'name5' => 'max:30',
            'name6' => 'max:30', 'name7' => 'max:30', 'name8' => 'max:30', 'name9' => 'max:30', 'name10' => 'max:30',
            'name11' => 'max:30', 'name12' => 'max:30', 'name13' => 'max:30', 'name14' => 'max:30', 'name15' => 'max:30',
            'name16' => 'max:30', 'name17' => 'max:30', 'name18' => 'max:30', 'name19' => 'max:30', 'name20' => 'max:30',

            'price1' => 'numeric', 'price2' => 'numeric', 'price3' => 'numeric', 'price4' => 'numeric', 'price5' => 'numeric',
            'price6' => 'numeric', 'price7' => 'numeric', 'price8' => 'numeric', 'price9' => 'numeric', 'price10' => 'numeric',
            'price11' => 'numeric', 'price12' => 'numeric', 'price13' => 'numeric', 'price14' => 'numeric', 'price15' => 'numeric',
            'price16' => 'numeric', 'price17' => 'numeric', 'price18' => 'numeric', 'price19' => 'numeric', 'price20' => 'numeric',

        ]);

        $option->store_id = $store->id;
        $option->name = $request->name;
        $option->min = $request->min;
        $option->max = $request->max;
        $option->save();

        //delete all option_options 

        $option->options()->delete();

        //create them again
        for($i = 1; $i<21; $i++){
            if($request['name'.$i] != ""){
                $options = new \App\menuOptionOption;
                $options->menu_option_id = $option->id;
                $options->name = $request['name'.$i];
                $options->price = $request['price'.$i];
                $options->save();
            }
        }

        flash('Option updated successfully.');

        return redirect('/manage/' . $storeSlug . '/options' );
    }



    public function optionsUpdateAvailable(Request $request, $storeSlug, $optionOptionId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        $option = \App\menuOptionOption::findOrFail($optionOptionId);

        if($option->menuOption->store_id != $store->id) abort(403); // item belongs to someone else

        //validate request
        $this->validate($request, [
            'available' => 'required|integer|min:0|max:1'
        ]);

        $option->available = $request->available;
        $option->save();
        
        flash('Option update successfully.');
        return redirect('/manage/' . $storeSlug . '/options' );
    }

















    public function coverage(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);


        return view('manage.coverage',compact('store'));
    }

// COVERAGE AREA

    public function coverageAreaCreate(Request $request, $storeSlug)
    {
        $countries = \Countries::getList('en', 'php', 'cldr');

        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);


        return view('manage.coverage-area-create',compact('store','countries'));
    }

    public function coverageAreaStore(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        $this->validate($request, [
            'country' => 'required|string',
            'city_id' => 'required|integer',
            'area_id' => 'required|integer',
            'min' => 'required|integer',
            'fee' => 'required|integer',
            'feebelowmin' => 'required|integer',
            'discount' => 'required|integer|between:0,100'
        ]);

        if ($store->coverageAreas->contains($request->area_id)) {
            $store->coverageAreas()->detach($request->area_id);
            flash('Area updated successfully.');
        }else{
            flash('Area added successfully.');
        }
        $store->coverageAreas()->attach($request->area_id, array('min' => $request->min, 'fee' => $request->fee, 'feebelowmin' => $request->feebelowmin, 'discount' => $request->discount));

        return redirect('/manage/' . $storeSlug . '/coverage' );
    }

    public function coverageAreaDestroy(Request $request, $storeSlug, $areaId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);


        $store->coverageAreas()->detach($areaId);

        flash('Area deleted successfully.');

        return redirect('/manage/' . $storeSlug . '/coverage' );
    }

// COVERAGE BUILDING

    public function coverageBuildingCreate(Request $request, $storeSlug)
    {
        $countries = \Countries::getList('en', 'php', 'cldr');

        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);


        return view('manage.coverage-building-create',compact('store','countries'));
    }

    public function coverageBuildingStore(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);


        $this->validate($request, [
            'country' => 'required|string',
            'city_id' => 'required|integer',
            'area_id' => 'required|integer',
            'building_id' => 'required|integer',
        //    'min' => 'required|integer',
        //    'fee' => 'required|integer',
        //    'feebelowmin' => 'required|integer'
            'discount' => 'required|integer|between:0,100'
        ]);

        if ($store->coverageBuildings->contains($request->building_id)) {
            $store->coverageBuildings()->detach($request->building_id);
            flash('Building updated successfully.');
        }else{
            flash('Building added successfully.');
        }
        // $store->coverageBuildings()->attach($request->building_id, array('min' => $request->min, 'fee' => $request->fee, 'feebelowmin' => $request->feebelowmin));
        $store->coverageBuildings()->attach($request->building_id, array('min' => 0, 'fee' => 0, 'feebelowmin' => 0, 'discount' => $request->discount));

        return redirect('/manage/' . $storeSlug . '/coverage' );
    }

    public function coverageBuildingDestroy(Request $request, $storeSlug, $areaId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);


        $store->coverageBuildings()->detach($areaId);

        flash('Building deleted successfully.');

        return redirect('/manage/' . $storeSlug . '/coverage' );
    }







/*
                dd($store->timings()->sortByDay());
        $timings = $store->timings()->sortBy(function ($product, $key) {
                                        return date('N', strtotime($day));
                                    });
*/


    public function timings(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);


        $workmodes = \App\Workmode::all();

        return view('manage.timings',compact('store','workmodes'));
    }


    public function timingsCreate(Request $request, $storeSlug, $workmodeid)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);


        $workmodes = \App\Workmode::findOrFail($workmodeid);
        $workmodes_list = [];
        //foreach($workmodes as $workmode)
                $workmodes_list[$workmodes->id] = $workmodes->name;


        return view('manage.timings-create',compact('store','workmodes_list'));
    }

    public function timingsStore(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);


        $this->validate($request, [
            'workmode_id' => 'required|integer',
            'day' => 'required|date_format:D',
            'start' => 'required|date_format:H:i:s',
            'end' => 'required|date_format:H:i:s|different:start',
        ]);

        // check that start is less than end
        $start = date_create_from_format('H:i:s', $request->start);
        $end = date_create_from_format('H:i:s', $request->end);
        if($start > $end){
            return back()
            ->withInput()
            ->withErrors("Start timing cannot be before end timing.")
            ->withInput();
        }

        if($request->everyday == "on" || $request->everyday == true){ //everyday

            flash('Timing created successfully.'); // set flash first if there is error it will be overwritten

            foreach(\Config::get('vars.days') as $day => $Day){

                //check that interval is not interfearing with another timings in the same mode
                $timing = $store->timings()->where('workmode_id',$request->workmode_id)->where('day',$day);
                $timing = $timing ->where(function ($query) use ($request){
                    $query->whereBetween('start', [$request->start,$request->end])
                          ->orwhereBetween('end', [$request->start,$request->end])
                          ->orWhere(function ($q) use ($request){
                            $q->where('start', '<=', $request->start)->where('end','>=',$request->end);
                          });
                })->get();

                if($timing->count() != 0){
                    flash("One or more timings conflicted with your existing ones. Double check the created.");
                }else{
                    $timing =  $store->timings()->create([
                                                        'workmode_id' => $request->workmode_id,
                                                        'start' => $request->start,
                                                        'end' => $request->end,
                                                        'day' => $day,
                                                        ]);
                }
            }
            
        }else{ // singleday

            //check that interval is not interfearing with another timings in the same mode
            $timing = $store->timings()->where('workmode_id',$request->workmode_id)->where('day',$request->day);
            $timing = $timing ->where(function ($query) use ($request){
                $query->whereBetween('start', [$request->start,$request->end])
                      ->orwhereBetween('end', [$request->start,$request->end])
                      ->orWhere(function ($q) use ($request){
                            $q->where('start', '<=', $request->start)->where('end','>=',$request->end);
                        });
            })->get();

            if($timing->count() != 0){
                return back()
                ->withInput()
                ->withErrors("Timing could not be created due to an conflict with your current timings.")
                ->withInput();
            }


            $timing =  $store->timings()->create($request->all());
            flash('Timing created successfully.');
        }


        return redirect('/manage/' . $storeSlug . '/timings' );
    }

    public function timingsDestroy(Request $request, $storeSlug, $timingId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        $timing = $store->timings()->where('id',$timingId)->delete();

        flash('Timing deleted successfully.');

        return redirect('/manage/' . $storeSlug . '/timings' );
    }

    public function timingsDestroyAll(Request $request, $storeSlug, $workmode_id)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        $timing = $store->timings()->where('workmode_id',$workmode_id)->delete();

        flash('Timings deleted successfully.');

        return redirect('/manage/' . $storeSlug . '/timings' );
    }




    public function users(Request $request, $storeSlug)
    {

        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store->userRole($request->user()->id) != 'store_owner') abort(403);

        $roles = \App\Role::where('name', 'LIKE', 'store_%')->get();
        $roles_list = array();
        foreach($roles as $role)
                $roles_list[$role->id] = $role->name;

        return view('manage.users',compact('store','roles_list'));
    }

    public function usersCreate(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store->userRole($request->user()->id) != 'store_owner') abort(403);

        $roles = \App\Role::where('name', 'LIKE', 'store_%')->get();
        $roles_list = array();
        foreach($roles as $role)
                $roles_list[$role->id] = $role->name;

        return view('manage.users-create',compact('store','roles_list'));
    }


    public function usersStore(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store->userRole($request->user()->id) != 'store_owner') abort(403);

        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = \App\User::where('email',$request->email)->first();

        //check if current user id return
        if($user->id == $request->user()->id){
            flash("You cannot change your own role.");
            return redirect('/manage/' . $storeSlug . '/users' );
        }

        $role = \App\Role::where('id', $request->role_id)->first();

        if(substr($role->name,0,6) !== "store_") abort(403);
        
        //delete all users with the same role
        $roles = $store->users()->detach($user->id);

        $role->users()->attach($user->id, ['store_id'=>$store->id]);

        return redirect('/manage/' . $storeSlug . '/users' );
    }


    public function usersDestroy(Request $request, $storeSlug, $userId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store->userRole($request->user()->id) != 'store_owner') abort(403);

        $user = \App\User::find($userId);
        if(!$user){
            flash("User does not exists.");
            return redirect('/manage/' . $storeSlug . '/users' );
        }

        //check if current user id return
        if($user->id == $request->user()->id){
            flash("You cannot delete your own role.");
            return redirect('/manage/' . $storeSlug . '/users' );
        }

        $roles = $store->users()->detach($user->id);

        flash('User deleted successfully.');

        return redirect('/manage/' . $storeSlug . '/users' );
    }




















    //tags
    public function tags(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        
        $tagslist = $store->tags->lists('id')->toArray();

        // dd($tagslist);

        return view('manage.tags',compact('store','tagslist'));
    }



    //tags
    public function tagsStore(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        $this->validate($request, [
            'tags' => 'required|array'
        ]);
        
        $store->tags()->sync($request->tags);

        flash('Tags updated successfully.');

        return redirect('/manage/' . $storeSlug . '/tags' );
    }




    //menu photos

    public function menuphotos(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        return view('manage.menuphoto',compact('store'));
    }




    public function menuphotosStore(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        //validate request
        $this->validate($request, [
            'imagefile' => 'required|mimes:jpg,jpeg,png,bmp',
            'photoCaption' => 'required|string|max:200'
        ]);

        $file = $request->file('imagefile');

        //store the new image
        $photoFileName = time() . '-' . str_random(5) . '-' . $store->slug . '.jpg' ;

        $image = \Image::make($file->getRealPath());
        $image->resize(700, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->resize(null, 700, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($this->menuImageBase.$photoFileName);


        $image = \Image::make($file->getRealPath());
        $image->fit(140,140)->save($this->menuImageBaseThumb.$photoFileName);

        $store->photos()->create(['type' => 'menu',
                                  'path' => $photoFileName,
                                  'text' => $request->photoCaption,
                                  'order' => $store->lastMenuPhotoOrder() + 1,
                                  'user_id' => $request->user()->id]);

        flash('Image uploaded successfully.');
    
        return redirect('/manage/'. $storeSlug . '/menu/photos');
    }



    public function menuphotoUp(Request $request, $storeSlug, $photoId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        

        $item = $store->photos()->where('id', $photoId)->first();
        if(!$item) abort(404);

        if($item->order != 0){
            $item->order = $item->order - 1;

            $item2 = $store->photos()->where('type','menu')->where('order', $item->order)->first();

            if($item2  != null){
                $item2->order = $item2->order + 1;
                $item2->save();
            }

            $item->save();
        }
        

        flash('Menu photo updated successfully.');
        return redirect('/manage/' . $storeSlug . '/menu/photos' );
    }

    public function menuphotoDown(Request $request, $storeSlug, $photoId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        $item = $store->photos()->where('id', $photoId)->first();
        if(!$item) abort(404);

        if($item->order != $store->lastMenuPhotoOrder()){
            $item->order = $item->order + 1;

            $item2 = $store->photos()->where('type','menu')->where('order', $item->order)->first();
                                  
            if($item2  != null){
                $item2->order = $item2->order - 1;
                $item2->save();
            }

            $item->save();
        }
        
        flash('Menu photo updated successfully.');
        return redirect('/manage/' . $storeSlug . '/menu/photos' );
    }

    public function menuphotoDestroy(Request $request, $storeSlug, $photoId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        $item = $store->photos()->where('id', $photoId)->first();
        if(!$item) abort(404);

        \File::delete($this->menuImageBase. $item->path); 
        \File::delete($this->menuImageBaseThumb. $item->path); 
        
        $item->delete();
        
        flash('Menu photo deleted successfully.');
        return redirect('/manage/' . $storeSlug . '/menu/photos' );
    }

    public function menuphotoEdit(Request $request, $storeSlug, $photoId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        $photo = $store->photos()->where('id', $photoId)->first();
        if(!$photo) abort(404);

        return view('manage.menuphoto-edit',compact('store', 'photo'));
    }

    public function menuphotoUpdate(Request $request, $storeSlug, $photoId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        $photo = $store->photos()->where('id', $photoId)->first();
        if(!$photo) abort(404);

        $this->validate($request, [
            'photoCaption' => 'required|string|max:200'
        ]);

        $photo->text = $request->photoCaption;
        $photo->save();


        flash('Menu photo edited successfully.');
        return redirect('/manage/' . $storeSlug . '/menu/photos' );
    }






























    //payments
    public function payments(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        
        $paymentslist = $store->payments->lists('id')->toArray();

        // dd($tagslist);

        return view('manage.payments',compact('store','paymentslist'));
    }

    public function paymentsStore(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        $this->validate($request, [
            'payments' => 'required|array'
        ]);
        
        $store->payments()->sync($request->payments);

        flash('Payments updated successfully.');

        return redirect('/manage/' . $storeSlug . '/payments' );
    }







    //zomato
    public function zomato(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array(\Auth::user()->id, \Config::get('vars.superAdmins'))) abort(403); // SUPERADMIN

        return view('manage.zomato.index',compact('store'));
    }



    private function add_to_options($options,$arr){
        //check if already exists
        $exists = 0;
        foreach($options as $option){
            if($option === $arr) $exists = 1;
        }
        if($exists == 0) $options[] = $arr;

        return $options;
    }

    private function get_option_id($options,$arr){
        //check if already exists
        $exists = 0;
        foreach($options as $key => $option){
            if($option === $arr) return $key;
        }
        return null;
    }


    public function zomatoProcess(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array(\Auth::user()->id, \Config::get('vars.superAdmins'))) abort(403); // SUPERADMIN

        \DB::beginTransaction();

        $this->validate($request, [
            'url' => 'required|url'
        ]);

        $res_id=0;
        $csrf = "";

        $client = new Client();
        $response = $client->request('GET', $request->url);
        $body = $response->getBody();
        


        $stringBody = (string) $body;

        if(preg_match('/var RES_ID = ([0-9]*);/', $stringBody, $matchesRes)){
            if(isset($matchesRes[1])) $res_id = $matchesRes[1];
        }

        if(preg_match('/zomato.csrft = "([^"]*)";/', $stringBody, $matchesC)){
            if(isset($matchesC[1])) $csrf = $matchesC[1];
        }

        if($res_id == 0 | $csrf == ""){
            flash("Could not process the url.");
            return redirect()->back();
        }

        $response = $client->request( 'POST', 'https://www.zomato.com/php/o2_handler.php', [
                    'form_params' => [
                        'case' => 'getdata',
                        'res_id' => $res_id,
                        'csrfToken' => $csrf
                    ]
                ]);

        $raw = (string) $response->getBody();

        $resData = json_decode($raw);

        $menuItem = [];
        $options = [];
        $sections = [];

        foreach($resData->menus as $menus){
            foreach($menus as $menu){
                foreach($menu->categories as $cats){
                    foreach($cats as $cat){

                        $sections[] = ['title'=>$cat->name, 'db'=>0];

                        foreach($cat->items as $item){
                            foreach($item as $i){

                                //reset option ids
                                $optionIds = [];
                                // {{$i->name}} - {{$i->price}} - {{$i->desc}} 
                                if(isset($i->groups)){
                                    foreach($i->groups as $group){
                                        foreach($group as $g){

                                            $tempGI = [];
                                            

                                            foreach($g->items as $groupItem){
                                                foreach($groupItem as $gi){
                                                    // {{ $gi->name }} {{ $gi->price }} {{ $gi->desc }}
                                                    $tempGI[] = ["name" => $gi->name, "price"=>$gi->price];
                                                }
                                            }

                                            $optionTemp = ["name" => $g->name, "min" => $g->min, "max" => $g->max, "options"=>$tempGI];

                                            $options = $this->add_to_options($options, $optionTemp);

                                            $newOptionId = $this->get_option_id($options, $optionTemp);
                                            if($newOptionId != null) $optionIds[] = $newOptionId;
                                        }
                                    }
                                }
                                $menuItem[] = ['title'=>$i->name, 'info'=>$i->desc, 'price'=>$i->price, 'section'=>count($sections)-1, 'options'=>$optionIds];
                            }
                        }
                    }
                }
            }
        }

        //add all sections 
        foreach($sections as $key => $section){
            
            $db_section = new \App\menuSection;
            $db_section->store_id = $store->id;
            $db_section->title = $section['title'];
            $db_section->order = $store->lastSectionOrder() + 1;
            $db_section->save();

            $sections[$key]['db'] =  $db_section->id;
        }

        //add options
        foreach($options as $key => $option){

            $db_option = new \App\menuOption;
            $db_option->store_id = $store->id;
            $db_option->name = $option['name'];
            $db_option->min = $option['min'];
            $db_option->max = $option['max'];
            $db_option->save();

            $options[$key]['db'] = $db_option->id;

            foreach($option['options'] as $keyOption => $optionOption){
                $db_options = new \App\menuOptionOption;
                $db_options->menu_option_id = $db_option->id;
                $db_options->name = $optionOption['name'];
                $db_options->price = $optionOption['price'];
                $db_options->save();
            }
        }


        //add items
        foreach($menuItem as $key => $mi){
            $db_mi = new \App\menuItem;
            $db_mi->menu_section_id = $sections[$mi['section']]['db'];
            $db_mi->title = $mi['title'];
            $db_mi->info = $mi['info'];
            $db_mi->price = $mi['price'];
            $db_mi->save();

            $syncArray = [];
            foreach($mi['options'] as $k => $o) $syncArray[] = $options[$o]['db']; 
            $db_mi->options()->sync($syncArray);
        }

        \DB::commit();

        $store->coordinate = $resData->restaurant->location->latitude . "," . $resData->restaurant->location->longitude ;
        $store->address = $resData->restaurant->location->address;
        $store->save();




        flash("Store imported Successfully");

        return redirect()->back();
    }




    //api
    public function getorders(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if($store->userRole($request->user()->id)==null) abort(403);

        $store->updateLastCheck();

        $orders = \App\Order::where('store_id',$store->id)->where('hidden_store',0)->with('user','userAddress','userAddress.area','userAddress.building','paymentType')->get();

        $returnData = array(
            'error' => 0,
            'orders' => $orders,
            'status_working' => $store->status_working
        );
        return response()->json($returnData);
    }

    public function orderUpdateStatus(Request $request, $storeSlug, $orderId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if($store->userRole($request->user()->id)==null) abort(403);

        $order = \App\Order::find($orderId);

        if($order == null) return $this->jsonOut('order_not_found','The order does not exists in our system.');

        if($order->store_id != $store->id) return $this->jsonOut('order_denied','The order does not belong to this store.');

        //check if order is changing log
        if($request->status != "" &&  $request->status != "callback" && $order->status != $request->status){
            $orderTime = new \App\OrderTime;
            $orderTime->user_id = $request->user()->id;
            $orderTime->order_id = $orderId;
            $orderTime->store_id = $store->id;
            $orderTime->status = $request->status;
            $orderTime->timestamp = time();
            $orderTime->save();
        }

        // check if delivered add comission
        if($order->status != 'delivered' && $request->status == 'delivered'){
            $tran = new \App\Transaction;
            $tran->user_id = $request->user()->id;
            $tran->store_id = $store->id;
            $tran->amount = round($order->price * $store->comission / 100, 2);
            $tran->type = 'credit';
            $tran->reference = 'Regarding order id ' . $order->id ;
            $tran->save();
        }

        if($request->hide == 1) $order->hidden_store = 1;

        if($order->status != "canceled"){
            if($request->reason != "") $order->reason = $request->reason;
            if($request->status != ""){
                if($request->status == "callback") $order->callback = 0;
                else $order->status = $request->status;
            }
        }

        $order->save();

        $orders = \App\Order::where('store_id',$store->id)->where('hidden_store',0)->with('user','userAddress','userAddress.area','userAddress.building')->get();

        $returnData = array(
            'error' => 0,
            'orders' => $orders
        );
        return response()->json($returnData);
    }


    public function updateStatusWorking(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if($store->userRole($request->user()->id)==null) abort(403);

        if(!in_array($request->status_working, ['open','busy','close']))
            return jsonOut(1,'Invalid status');

        $store->status_working = $request->status_working;
        $store->save();

        return jsonOut(0,'ok');
    }



}
