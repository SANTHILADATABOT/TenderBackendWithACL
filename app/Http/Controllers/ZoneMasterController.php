<?php

namespace App\Http\Controllers;

use App\Models\ZoneMaster;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;

class ZoneMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $zonemaster=ZoneMaster::select('*')->get();
        return response()->json([
            'status' => 200,
            'zonemaster' => $zonemaster]);
    }

    
    public function create()
    {
        
    }

    
    public function store(Request $request)
    {
        $user = Token::where("tokenid", $request->tokenId)->first();
        if($user['userid'])
        {
        $zone = ZoneMaster::where('zone_name', '=', $request->zonename)->exists();
        if ($zone) {
            return response()->json([
                'status' => 400,
                'errors' => 'Zone Name Already Exists'
            ]);
        }

        $validator = Validator::make($request->all(), ['zonename' => 'required|string', 'status' => 'required|string']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        
        $zone = new ZoneMaster;
        $zone->zone_name = $request->zonename;
        $zone->active_status=$request->status;
        $zone->save();
        
        
        if ($zone) {
            return response()->json([
                'status' => 200,
                'message' => 'Tender Type Added Succssfully!'
            ]);
        }
        }

    }

    
    public function show(ZoneMaster $zoneMaster)
    {
        //
    }

    
    public function edit(ZoneMaster $zoneMaster)
    {
        //
    }

    
    public function update(Request $request, ZoneMaster $zoneMaster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ZoneMaster  $zoneMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(ZoneMaster $zoneMaster)
    {
        //
    }
}
