<?php

namespace App\Http\Controllers;

use App\Models\ZoneMaster;
use App\Models\ZoneHasState;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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

        
        foreach($request->statelist as $row)
        {
            $zonehasstate = new ZoneHasState;
            $zonehasstate->zone_id= $zone->id;
            $zonehasstate->state_id= $row['value'];
            $zonehasstate->save();
        }
        

        if ($zone && $zonehasstate) {
            return response()->json([
                'status' => 200,
                'message' => 'Tender Type Added Succssfully!'
            ]);
        }
        }

    }

    
    public function show($id)
    {
        try{
        $zonemaster = DB::table('zone_masters as z')
        ->join('zone_has_states as sub','z.id','sub.zone_id')
        ->join('state_masters as s','sub.state_id','s.id')
        ->select('z.id as zone_id','z.zone_name','z.active_status','s.id as state_id','s.state_name')
        ->where('z.id',$id)
        ->get();

        if($zonemaster){
            $zone['zone_id'] = $zonemaster[0]->zone_id;
            $zone['zone_name'] = $zonemaster[0]->zone_name;
            $zone['active_status'] = $zonemaster[0]->active_status;
            $i=0;
            foreach($zonemaster as $z)
            {
                $zone['statelist'][$i] = ['value' => $z->state_id, 'label' => $z->state_name];
                $i++;
            }

            return response()->json([
                'status' => 200,
                'zonename' => $zone
            ]);
        }
    }
    catch(\Exception $ex){
        return response()->json([
            'status' => 400,
            'message' => 'Unable to handle',
            'error' => $ex
        ]);
    }
    }

    
    public function edit(ZoneMaster $zoneMaster)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
       
        $user = Token::where("tokenid", $request->tokenId)->first();
        if($user['userid'])
        {
        $zone = ZoneMaster::find($id);
        if (!$zone) {
            return response()->json([
                'status' => 400,
                'errors' => 'Somthing Wenr Wrong'
            ]);
        }

        $validator = Validator::make($request->all(), ['zonename' => 'required|string', 'status' => 'required|string']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        
        
        $zone->zone_name = $request->zonename;
        $zone->active_status=$request->status;
        $zone->save();
        $zonehasstate = ZoneHasState::where('zone_id',$id)->get();
        
        foreach($request->statelist as $row)
        {
            // return $row;
            foreach($zonehasstate as $zs)
           {
        //    echo "  -- Zs".collect($zs);
            if($zs->state_id == $row['value'])
            {
                echo " --- True   -- $row[value]  ";
            } 
            else{
                echo " --- False  --- $row[value]";
                $zone_delete = ZoneHasState::destroy($zs->id);
                

            }
        }
        //    echo "  -- $row  ---";
            // $zonehasstate->zone_id= $zone->id;
            // $zonehasstate->state_id= $row['value'];
            // $zonehasstate->save();
        }
        
      

        if ($zone && $zonehasstate) {
            return response()->json([
                'status' => 200,
                'message' => 'Tender Type Added Succssfully!'
            ]);
        }
        }
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
