<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use Illuminate\Support\Str;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ManageController extends Controller
{


    protected $menuBase = 'img/menu/';
    protected $logoBase = 'img/logo/';
    protected $coverBase = 'img/cover/';
    protected $coverMobileBase = 'img/cover-mobile/';
   
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
            'email' => 'required|email'
        ]);

        // save the store into the db
        $store = new \App\Store;
        $store->name = $request->name;
        $store->slug = Str::slug($request->name);
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












    public function reports(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        return view('manage.reports',compact('store'));
    }



    public function reportsOrderShow(Request $request, $storeSlug, $orderId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        $order = $store->orders()->find($orderId);
        if(!$order) abort(404);


        return view('manage.reports-order',compact('store', 'order'));
    }


    public function billing(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);
        
        return view('manage.billing',compact('store'));
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
        ]);

        // save the store into the db
        $store->name = $request->name;
        $store->phone = $request->phone;
        $store->email = $request->email;
        $store->info = $request->info;
        $store->status_working = $request->status_working;
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
        $photoFileName = time() . '-' . $file->getClientOriginalName();
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
        $photoFileName = time() . '-' . $file->getClientOriginalName();
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
            'building_id' => 'required|integer'
        ]);

        // save the store into the db
        $store->country = $request->country;
        $store->city_id = $request->city_id;
        $store->area_id = $request->area_id;
        $store->building_id = $request->building_id;
        $store->save();

        flash('Store location updated successfully.');
        return redirect('/manage/' . $storeSlug . '/general');
    }











    public function menu(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
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
            'title' => 'required|max:100'
        ]);

        $section = new \App\menuSection;
        $section->store_id = $store->id;
        $section->title = $request->title;
        $section->order = $store->lastSectionOrder() + 1;
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

            $item2 = \App\menuSection::where('order', $item->order)->first();

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


        if($item->order != $store->lastSectionOrder()){
            $item->order = $item->order + 1;

            $item2 = \App\menuSection::where('order', $item->order)->first();
                                  
            if($item2  != null){
                $item2->order = $item2->order - 1;
                $item2->save();
            }

            $item->save();
        }
        

        flash('Menu section updated successfully.');
        return redirect('/manage/' . $storeSlug . '/menu' );
    }






































    public function menuItemCreate(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        return view('manage.menu-item',compact('store'));
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
            'imagefile' => 'mimes:jpg,jpeg,png,bmp'
        ]);

        //dd($_POST['options']);
        //dd($request->options);

        $section = \App\menuSection::findOrFail($request->menu_section_id);
        if($section->store_id != $store->id) abort(403);

        $item = $section->items()->create($request->all());

        if(is_array($request->options)) $item->options()->sync($request->options);

        //handle image
        $file = $request->file('imagefile');
        if($file){
            //delete the old picture
            //if($item->photo) \File::delete($this->menuBase.$item->photo);

            //store the new image
            $photoFileName = time() . '-' . $file->getClientOriginalName();
            $image = \Image::make($file->getRealPath());
            $image->fit(150,150)->save($this->menuBase.$photoFileName);

            //update db
            $item->photo = $photoFileName;
            $item->save();
        }


        flash('Item created successfully.');
        return redirect('/manage/' . $storeSlug . '/menu' );
    }

    public function menuItemDestroy(Request $request, $storeSlug, $itemId)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);

        $item = \App\menuItem::findOrFail($itemId);

        if($store->items()->get()->where('id',$item->id)->isEmpty()) abort(403); // item belongs to someone else

        // delete photo
        if($item->photo) \File::delete($this->menuBase.$item->photo);

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

        $sectionList = $store->sections()->lists('title', 'id');

        return view('manage.menu-item-edit',compact('store','item','sectionList'));
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

        $item->menu_section_id = $request->menu_section_id;
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

        //handle image
        $file = $request->file('imagefile');
        if($file){
            //delete the old picture
            if($item->photo) \File::delete($this->menuBase.$item->photo);

            //store the new image
            $photoFileName = time() . '-' . $file->getClientOriginalName();
            $image = \Image::make($file->getRealPath());
            $image->fit(150,150)->save($this->menuBase.$photoFileName);

            //update db
            $item->photo = $photoFileName;
            $item->save();
        }





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


    public function timingsCreate(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if(!in_array($store->userRole($request->user()->id),['store_owner','store_manager'])) abort(403);


        $workmodes = \App\Workmode::all();
        $workmodes_list = array();
        foreach($workmodes as $workmode)
                $workmodes_list[$workmode->id] = $workmode->name;


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


        //check that interval is not interfearing with another timings in the same mode

        $timing = $store->timings()->where('workmode_id',$request->workmode_id)->where('day',$request->day);
        $timing = $timing ->where(function ($query) use ($request){
            $query->whereBetween('start', [$request->start,$request->end])
                  ->orwhereBetween('end', [$request->start,$request->end]);
        })->get();

        if($timing->count() != 0){
            return back()
            ->withInput()
            ->withErrors("Timing could not be created due to an conflict with your current timings.")
            ->withInput();
        }


        $timing =  $store->timings()->create($request->all());
        flash('Timing created successfully.');
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

        
        $store->tags()->sync($request->tags);

        flash('Tags updated successfully.');

        return redirect('/manage/' . $storeSlug . '/tags' );
    }









    //api
    public function getorders(Request $request, $storeSlug)
    {
        $store = \App\Store::where('slug',$storeSlug)->first();
        if($store == null ) abort(404);
        if($store->userRole($request->user()->id)==null) abort(403);

        $store->updateLastCheck();

        $orders = \App\Order::where('store_id',$store->id)->where('hidden_store',0)->with('user','userAddress','userAddress.area','userAddress.building')->get();

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
        if($request->reason != "") $order->reason = $request->reason;
        if($request->status != ""){
            if($request->status == "callback") $order->callback = 0;
            else $order->status = $request->status;
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
