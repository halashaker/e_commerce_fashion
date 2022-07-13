<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
class LocationController extends Controller
{

     public function index()
    {
        $locations = Location::all();

        $response['data'] = $locations;
        $response['message'] = "This is all locations";
        $response['status_code'] = 200;
        return response()->json($response,200) ;
    }
    public function store(Request $request)
    {
       $location =new Location;
           $location->name = $request->name;
           $location->state_id = $request->state_id;
         
           $location->save();

            $response['data'] = $location;
            $response['message'] = "store success";
            $response['status_code'] = 200;
            return response()->json($response,200) ;
       
    }
     public function show_location_by_id($state_id)
    {
        $location = location::all()->where('state_id','=', $state_id );
        if (isset($location)) {
        $response['data'] = $location->values();
        $response['message'] = "success";
        $response['status_code'] = 200;
        return response()->json($response,200) ;
        }
        $response['data'] =$location->values();
        $response['message'] = "error";
        $response['status_code'] = 404;
        return response()->json($response,404) ;
    }
}
