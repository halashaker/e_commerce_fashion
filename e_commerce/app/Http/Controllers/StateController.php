<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\State;
class StateController extends Controller
{
   public function index()
    {
        $states = State::all();

        $response['data'] = $states;
        $response['message'] = "This is all locations";
        $response['status_code'] = 200;
        return response()->json($response,200) ;
    }
    public function store(Request $request)
    {
       $state =new State;
           $state->name = $request->name;
           $state->save();

            $response['data'] = $state;
            $response['message'] = "store success";
            $response['status_code'] = 200;
            return response()->json($response,200) ;           
    }
}
