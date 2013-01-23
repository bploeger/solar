<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('zip/{zip}', function($zip)
{
    $zipinfo = zipcode::where('zip', '=', $zip)->first();
    if ($zipinfo == null) {
        return "Error: Zipcode not found";
    }
    $lat = round($zipinfo->lat,2);
    $lon = round($zipinfo->lon,2);

    return Calc::solar($lat, $lon);

});

Route::get('lookup/{city}/{state}', function($city, $state)
{
    $city = strtoupper($city);
    $state = strtoupper($state);
    $zipinfo = zipcode::where('city', '=', $city)->where('state','=',$state)->first();
    if ($zipinfo == null) {
        return "Error: City and State not found";
    }
    $lat = round($zipinfo->lat,2);
    $lon = round($zipinfo->lon,2);

    return Calc::solar($lat, $lon);
});

Route::get('coord/{lat}/{lon}', function($lat, $lon)
{

    if (abs($lon)>180)
    {
        return "Error: Longitude out of range";
    }

    if (abs($lat)>66.75)
    {
        return "Error: Latitude out of range";
    }
    $lat = round($lat,2);
    $lon = round($lon,2);

    return Calc::solar($lat, $lon);
});