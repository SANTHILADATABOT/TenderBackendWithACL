<?php

namespace App\Http\Controllers;

use App\Models\CallCloseStatuses;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CallCloseStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return "create function";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    
    public function show($id)
    {
        //
    }

   
    public function edit()
    {
        return "edit function";
    }

   
    public function update(Request $request, $id)
    {
        //
    }

   
    public function destroy($id)
    {
       //
    }

    public function getCallCloseStatusList()
    {
        $ccstatus_list = CallCloseStatuses::where("active_status", "=", "active")->get();
        $ccList = [];
        foreach($ccstatus_list as $row){
            $ccList[] = ["value" => $row['id'], "label" =>  $row['name']] ;
        }
        return response()->json([
            'callclosestatus' =>  $ccList,
        ]);
    }
}
