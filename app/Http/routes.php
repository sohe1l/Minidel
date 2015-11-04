<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/




      //  \DB::connection()->enableQueryLog();




/* Super Admin */
Route::group(['prefix' => 'superadmin' , 'middleware' => 'auth.superadmin'], function () {
    Route::get('/','Superadmin\AdminController@index');

    /* Locations */
    Route::get('location','Superadmin\LocationController@index');
    
    Route::get('location/{country}/','Superadmin\LocationController@countryIndex'); //view all cities
    Route::post('location/{country}/city','Superadmin\LocationController@cityStore'); // to save a new city
    
    Route::get('location/{country}/{city}','Superadmin\LocationController@cityIndex'); // to view all areas
    Route::post('location/{country}/{city}/area','Superadmin\LocationController@areaStore'); // to save new areas

    Route::get('location/{country}/{city}/{area}','Superadmin\LocationController@areaIndex'); // to view all buildings
    Route::post('location/{country}/{city}/{area}/building','Superadmin\LocationController@buildingStore'); // to save new building
});






/* Pages */
Route::get('pages/addstore','PagesController@addStore');
Route::get('pages/contact','PagesController@contact');
Route::get('pages/privacy','PagesController@privacy');
Route::get('pages/terms','PagesController@terms');
Route::get('pages/about','PagesController@about');






// Dashboard
Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function () {
    Route::get('/', 'DashboardController@index');
    Route::post('regular', 'DashboardController@orderRegular');

    Route::get('order', 'DashboardController@order');
    Route::post('order/stores', 'DashboardController@orderStores');

    Route::get('orders', 'DashboardController@orders');
    Route::post('orders/getorders', 'DashboardController@getorders');
    Route::post('orders/{id}/updateStatus', 'DashboardController@orderUpdateStatus');

    //general
    Route::get('general','DashboardController@generalIndex');
    Route::post('general','DashboardController@generalStore');
    Route::post('dp','DashboardController@dpStore');

    //password
    Route::get('password','DashboardController@passwordIndex');
    Route::post('password','DashboardController@passwordStore');

    // resource address
    Route::get('address','DashboardController@addressIndex');
    Route::get('address/create','DashboardController@addressCreate');
    Route::post('address','DashboardController@addressStore');
    Route::delete('address/{Id}','DashboardController@addressDestroy');
    Route::get('address/{Id}/edit','DashboardController@addressEdit');
    Route::put('address/{itemId}','DashboardController@addressUpdate');

});




/* Manage Stores */

Route::group(['prefix' => 'manage', 'middleware' => 'auth'], function () {
    Route::get('/', 'ManageController@index');
    Route::get('create','ManageController@create');
    Route::post('create','ManageController@store');

    Route::get('{store}/','ManageController@show');

    //api
    Route::post('{store}/getorders','ManageController@getorders'); // get list of orders
    Route::post('{store}/order/{id}/updateStatus','ManageController@orderUpdateStatus');
    Route::post('{store}/updateStatusWorking','ManageController@updateStatusWorking');

    Route::post('{store}/submitReview','ManageController@submitReview');



    Route::get('{store}/reports','ManageController@reports');
    Route::get('{store}/reports/order/{id}','ManageController@reportsOrderShow');
    Route::get('{store}/billing','ManageController@billing');




    Route::get('{store}/general','ManageController@general');
    Route::post('{store}/general','ManageController@generalStore'); // save general store information
    Route::post('{store}/logo','ManageController@logoStore'); // save store logo
    Route::post('{store}/cover','ManageController@coverStore'); // save store cover

    Route::get('{store}/location','ManageController@location');
    Route::post('{store}/location','ManageController@locationStore');
    Route::post('{store}/location/marker','ManageController@locationMarkerStore');
    


    Route::get('{store}/menu','ManageController@menu');

    Route::post('{store}/menu/section','ManageController@menuSectionStore');
    Route::delete('{store}/menu/section/{sectionId}','ManageController@menuSectionDelete');
    Route::get('{store}/menu/section/{sectionId}/up','ManageController@menuSectionUp');
    Route::get('{store}/menu/section/{sectionId}/down','ManageController@menuSectionDown');
    Route::put('{store}/menu/section/{sectionId}/available','ManageController@menuSectionUpdateAvailable');

    // resource menu/item
    Route::get('{store}/menu/item/create','ManageController@menuItemCreate');
    Route::post('{store}/menu/item','ManageController@menuItemStore');
    Route::delete('{store}/menu/item/{itemId}','ManageController@menuItemDestroy');
    Route::get('{store}/menu/item/{itemId}/edit','ManageController@menuItemEdit');
    Route::put('{store}/menu/item/{itemId}','ManageController@menuItemUpdate');
    Route::get('{store}/menu/item/{itemId}/up','ManageController@menuItemUp');
    Route::get('{store}/menu/item/{itemId}/down','ManageController@menuItemDown');
    Route::put('{store}/menu/item/{itemId}/available','ManageController@menuItemUpdateAvailable');

    // resource menu/options
    Route::get('{store}/options','ManageController@options');
    Route::get('{store}/options/create','ManageController@optionsCreate');
    Route::post('{store}/options/','ManageController@optionsStore');
    Route::delete('{store}/options/{optionId}','ManageController@optionsDestroy');
    Route::get('{store}/options/{optionId}/edit','ManageController@optionsEdit');
    Route::put('{store}/options/{optionId}','ManageController@optionsUpdate');
    Route::put('{store}/options/{optionId}/available','ManageController@optionsUpdateAvailable');



    //coverage
    Route::get('{store}/coverage','ManageController@coverage');
    Route::get('{store}/coverage/area/create','ManageController@coverageAreaCreate');
    Route::post('{store}/coverage/area','ManageController@coverageAreaStore');
    Route::delete('{store}/coverage/area/{id}','ManageController@coverageAreaDestroy');
    Route::get('{store}/coverage/building/create','ManageController@coverageBuildingCreate');
    Route::post('{store}/coverage/building','ManageController@coverageBuildingStore');
    Route::delete('{store}/coverage/building/{id}','ManageController@coverageBuildingDestroy');

    //timings
    Route::get('{store}/timings','ManageController@timings');
    Route::get('{store}/timings/create','ManageController@timingsCreate');
    Route::post('{store}/timings','ManageController@timingsStore');
    Route::delete('{store}/timings/{id}','ManageController@timingsDestroy');

    //users
    Route::get('{store}/users','ManageController@users');
    Route::get('{store}/users/create','ManageController@usersCreate');
    Route::post('{store}/users','ManageController@usersStore');
    Route::delete('{store}/users/{id}','ManageController@usersDestroy');

    // tags
    Route::get('{store}/tags','ManageController@tags');
    Route::post('{store}/tags','ManageController@tagsStore');

    // Photo
    Route::get('{store}/menu/photos','ManageController@menuphotos');
    Route::post('{store}/menu/photos','ManageController@menuphotosStore');
    Route::get('{store}/menu/photo/{photoId}/up','ManageController@menuphotoUp');
    Route::get('{store}/menu/photo/{photoId}/down','ManageController@menuphotoDown');
    Route::delete('{store}/menu/photo/{id}','ManageController@menuphotoDestroy');
    Route::get('{store}/menu/photo/{id}/edit','ManageController@menuphotoEdit');
    Route::put('{store}/menu/photo/{id}','ManageController@menuphotoUpdate');



    // payments
    Route::get('{store}/payments','ManageController@payments');
    Route::post('{store}/payments','ManageController@paymentsStore');
});


Route::get('/', function () {
    return view('welcome');
});




Route::get('auth/facebook', 'Auth\AuthController@redirectToProvider');
Route::get('auth/facebook/callback', 'Auth\AuthController@handleProviderCallback');


Route::get('auth/confirm/{token}','Auth\AuthController@confrimEmail');

Route::get('auth/confirm/','Auth\AuthController@confrimView');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController'
]);

/* Api */
Route::group(['prefix' => 'api/v1/'], function () {
    Route::get('country/{country}/cities','Apiv1Controller@listCities');
    Route::get('country/{country}/cities/{city}/areas','Apiv1Controller@listAreas');
    Route::get('country/{country}/cities/{city}/areas/{area}/buildings','Apiv1Controller@listBuildings');
});






/*
//browse by where they delivery to
Route::get('delivery/{city}','BrowseController@deliveryCity');
Route::get('delivery/{city}/{area}','BrowseController@deliveryArea');
Route::get('delivery/{city}/{area}/{building}','BrowseController@deliveryBuilding');
*/


//browse by location
Route::get('browse','BrowseController@browseIndex');
Route::get('browse/{city}','BrowseController@city');
Route::get('browse/{city}/{area}','BrowseController@area');
Route::get('browse/{city}/{area}/{building}','BrowseController@building');

/*

//store page
Route::get('{city}/{area}/{store}','BrowseController@store');
Route::post('{city}/{area}/{store}/photo','BrowseController@storePhoto');
Route::get('{city}/{area}/{store}/order','BrowseController@storeOrder'); // save orders for a store
Route::post('{city}/{area}/{store}/order','BrowseController@storeOrderStore'); // save orders for a store
Route::get('{city}/{area}/{store}/reviews','BrowseController@storeReviews');
Route::post('{city}/{area}/{store}/reviews','BrowseController@storeReviewsStore');
*/

//////////////// DOUPLICATES
/*
Route::group(['middleware' => 'auth'], function () {


});
*/
Route::post('{store}/photo','BrowseController@storePhoto');
Route::post('{store}/reviews','BrowseController@storeReviewsStore');

Route::get('{store}/order','BrowseController@storeOrder'); // save orders for a store
Route::post('{store}/order','BrowseController@storeOrderStore'); // save orders for a store
Route::get('{store}/reviews','BrowseController@storeReviews');

//////////////// DOUPLICATES

Route::get('search','BrowseController@search');
Route::post('search','BrowseController@searchPost');


Route::get('{usernameOrStore}','BrowseController@profileOrStore');