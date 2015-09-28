<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    public function index()
    {
        // $countries = \Countries::getList('en', 'php', 'cldr');
        return view('admin.location.index');
    }

    public function countryIndex($countrySlug)
    {
        //load cities for that country
        $cities = \App\City::where('country', $countrySlug)->get();
        return view('admin.location.country', compact('cities', 'countrySlug'));
    }




    /* Store a new city (post request) */
    public function cityStore(Request $request, $countrySlug)
    {
        $this->validate($request, [
            'country' => 'required',
            'name' => 'required',
            'slug' => 'required',
            'areacode' => 'required|integer'
        ]);

        $city = \App\City::create( $request->all() );

        return redirect('/superadmin/location/'. $countrySlug);
    }




    public function cityIndex($countrySlug, $citySlug)
    {
        $city = \App\City::where('country',$countrySlug)->where('slug',$citySlug)->first();
        // $areas = $city->areas;
        return view('admin.location.city', compact('city'));
    }


    /* Store a new city (post request) */
    public function areaStore(Request $request, $countrySlug, $citySlug)
    {
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required'
        ]);

        $city = \App\City::where('country',$countrySlug)->where('slug',$citySlug)->first();
        $city->areas()->create( $request->all() );

        return redirect('/superadmin/location/'.$countrySlug.'/'.$citySlug);
    }








    public function areaIndex($countrySlug, $citySlug, $areaSlug)
    {
        $city = \App\City::where('country',$countrySlug)->where('slug',$citySlug)->first();

        $area = $city->areas()->where('slug',$areaSlug)->first();


        // $areas = $city->areas;
        return view('admin.location.area', compact('area', 'city'));
    }


    /* Store a new city (post request) */
    public function buildingStore(Request $request, $countrySlug, $citySlug, $areaSlug)
    {
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required'
        ]);

        $city = \App\City::where('country',$countrySlug)->where('slug',$citySlug)->first();

        $area = $city->areas()->where('slug',$areaSlug)->first();
        
        $area->buildings()->create( $request->all() );

        return redirect('/superadmin/location/'.$countrySlug.'/'.$citySlug.'/'.$areaSlug);
    }

}
